<?php
/**
 * ClassController.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherClientBundle\Controller;


use ArthurHoaro\RssCruncherClientBundle\Entity\Client;
use ArthurHoaro\RssCruncherClientBundle\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    /**
     * Lists all Client entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ArthurHoaroRssCruncherClientBundle:Client')->findAll();

        return $this->render(
            '@ArthurHoaroRssCruncherClient/Client/index.html.twig',
            ['entities' => $entities]
        );
    }

    /**
     * Creates a new Client.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $entity = new Client();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $clientManager = $this->get('fos_oauth_server.client_manager.default');
            $entity->setAllowedGrantTypes(['token', 'authorization_code', 'refresh_token', 'kaka']);
            $clientManager->updateClient($entity);
            return $this->redirect(
                $this->generateUrl(
                    'arthur_hoaro_rss_cruncher_client_show',
                    ['id' => $entity->getId()]
                )
            );
        }

        $validator = $this->get('validator');
        $errors = $validator->validate($form);

        return $this->render('@ArthurHoaroRssCruncherClient/Client/new.html.twig',
            [
                'entity' => $entity,
                'form'   => $form->createView(),
                'errors' => $form->getErrors(true, false),
            ]
        );
    }

    /**
     * Creates a form to create a Testent entity.
     *
     * @param Client $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Client $entity)
    {
        $form = $this->createForm(ClientType::class, $entity, array(
            'action' => $this->generateUrl('arthur_hoaro_rss_cruncher_client_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Client entity.
     */
    public function newAction()
    {
        $entity = new Client();
        $form   = $this->createCreateForm($entity);

        return $this->render(
            '@ArthurHoaroRssCruncherClient/Client/new.html.twig',
            [
                'entity' => $entity,
                'form'   => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a Testent entity.
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ArthurHoaroRssCruncherClientBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            '@ArthurHoaroRssCruncherClient/Client/show.html.twig',
            [
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Testent entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ArthurHoaroRssCruncherClientBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            '@ArthurHoaroRssCruncherClient/Client/edit.html.twig',
            [
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]
        );
    }

    /**
     * Creates a form to edit a Testent entity.
     *
     * @param Client $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Client $entity)
    {
        $form = $this->createForm(ClientType::class, $entity, array(
            'action' => $this->generateUrl('arthur_hoaro_rss_cruncher_client_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Client entity.
     *
     * @param Request $request
     * @param int     $id      client ID.
     *
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ArthurHoaroRssCruncherClientBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $message = ['success' => 'Client updated!'];
        } else {
            $message = ['errors' => $editForm->getErrors(true, false)];
        }

        return $this->render('@ArthurHoaroRssCruncherClient/Client/edit.html.twig',
            array_merge(
                $message,
                [
                    'entity'      => $entity,
                    'edit_form'   => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ]
            )
        );
    }

    /**
     * Deletes a Testent entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ArthurHoaroRssCruncherClientBundle:Client')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Client entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('arthur_hoaro_rss_cruncher_client_index'));
    }

    /**
     * Creates a form to delete a Testent entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('arthur_hoaro_rss_cruncher_client_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
            ;
    }
}