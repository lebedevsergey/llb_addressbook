<?php

/**
 * Addressbook entries controller
 */

namespace App\Controller;

use App\Entity\Addressee;
use App\Form\AddresseeType;
use App\Repository\AddresseeRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Controller used to manage blog contents in the public part of the site.
 *
 * @Route("/addressee")
 *
 */
class AddresseeController extends AbstractController
{
    const DEFAULT_USER_PICTURE_FILENAME = '';

    /**
     * @Route("/", defaults={"page": "1"}, methods="GET", name="addressee_index")
     * @Route("/page/{page<[1-9]\d*>}", methods="GET", name="addressee_index_paginated")
     * @Cache(smaxage="10")
     *
     * NOTE: For standard formats, Symfony will also automatically choose the best
     * Content-Type header for the response.
     * See https://symfony.com/doc/current/routing.html#special-parameters
     */
    public function index(Request $request, int $page, AddresseeRepository $repo): Response
    {
        $addressees = $repo->getAll($page);

        return $this->render('addressee/index.html.twig', [
            'paginator' => $addressees,
        ]);
    }

    /**
     * @Route("/search", methods="GET", name="addressee_search")
     */
    public function search(Request $request, AddresseeRepository $repo): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->render('addressee/search.html.twig');
        }

        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', AddresseeRepository::NUM_ITEMS);
        $foundItems = $repo->findBySearchQuery($query, $limit);

        $results = [];
        foreach ($foundItems as $item) {
            $results[] = [
                'id' => $item->getId(),
                'firstname' => htmlspecialchars($item->getFirstname(), ENT_COMPAT | ENT_HTML5),
                'lastname' => htmlspecialchars($item->getLastname(), ENT_COMPAT | ENT_HTML5),
                'birthdate' => $item->getBirthday()->format('D M Y'),
                'phone_number' => htmlspecialchars($item->getPhone(), ENT_COMPAT | ENT_HTML5),
                'email' => htmlspecialchars($item->getEmail(), ENT_COMPAT | ENT_HTML5),
            ];
        }

        return $this->json($results);
    }

    /**
     * @Route("/{id}", methods="GET", name="entry_show")
     *
     */
    public function show(Addressee $item): Response
    {
//        dd($item);
        return $this->render('addressee/item/show.html.twig', ['item' => $item]);
    }

    /**
     * Displays a form to edit an existing Entry entity.
     *
     * @Route("/{id<\d+>}/edit", methods="GET|POST", name="entry_edit")
     */
    public function edit(Request $request, Addressee $item, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(AddresseeType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('picture')) {
                $this->uploadAddresseeImage($form, $fileUploader, $item);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'entry.updated_successfully');

            return $this->redirectToRoute('entry_show', ['id' => $item->getId()]);
        }

        return $this->render('addressee/item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Post entity.
     *
     * @Route("/{id}/delete", methods="POST", name="entry_delete")
     */
    public function delete(Request $request, Addressee $item): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('addressee_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();

        $this->addFlash('success', 'entry.deleted_successfully');

        return $this->redirectToRoute('addressee_index');
    }

    /**
     * Creates a new Entry entity.
     *
     * @Route("/item/new", methods="GET|POST", name="entry_new")
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $item = new Addressee();

        $form = $this->createForm(AddresseeType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadAddresseeImage($form, $fileUploader, $item);

            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            $this->addFlash('success', 'entry.created_successfully');

            return $this->redirectToRoute('addressee_index');
        }

        return $this->render('addressee/item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param FormInterface $form
     * @param SluggerInterface $slugger
     * @param string $fieldName
     * @param string $defaultFilename
     * @return string uploaded file name
     */
    private function uploadFile(FormInterface $form, SluggerInterface $slugger, string $fieldName, string $defaultFilename, string $uploadFolderName): string
    {
        $pictureFile = $form->get($fieldName)->getData();
        if (!$pictureFile) {
            return $defaultFilename;
        }

        $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

        try {
            $pictureFile->move(
                $uploadFolderName,
                $newFilename
            );

            return $newFilename;

        } catch (FileException $e) {
            return $defaultFilename;
        }
    }

    private function uploadAddresseeImage(FormInterface $form, FileUploader $fileUploader, Addressee $item)
    {
        $fileData = $form->get('picture')->getData();
        if (!$fileData) {
            return self::DEFAULT_USER_PICTURE_FILENAME;
        }

        $uploadedFileName = $fileUploader->upload($pictureFile = $fileData, $this->getParameter('pictures_directory'));

        $oldPictureUrl = $item->getPictureUrl();
        $newPictureUrl = $this->getAddresseePictureUrl($uploadedFileName);

        if ($oldPictureUrl !== $newPictureUrl) {
            $item->setPictureUrl($newPictureUrl);
        }

        if (!empty($oldPictureUrl) && $oldPictureUrl !== $newPictureUrl && $oldPictureUrl !== self::DEFAULT_USER_PICTURE_FILENAME) {
            try {
                unlink($this->getParameter('pictures_directory') . '/' . basename($oldPictureUrl));
            } catch (\Exception $e) {
                // pass it
            }
        }
    }

    private function getAddresseePictureUrl(string $filename): string
    {
        return $this->getParameter('pictures_location') . '/'. $filename;
    }
}