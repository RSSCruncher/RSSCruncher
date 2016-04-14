<?php

namespace ArthurHoaro\RssCruncherClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ArthurHoaro\RssCruncherClientBundle\Entity\Testent;
use ArthurHoaro\RssCruncherClientBundle\Form\TestentType;

/**
 * Testent controller.
 *
 * @Route("/testent")
 */
class TestentController extends Controller
{

    /**
     * Lists all Testent entities.
     *
     * @Route("/", name="testent")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ArthurHoaroRssCruncherClientBundle:Testent')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Testent entity.
     *
     * @Route("/", name="testent_create")
     * @Method("POST")
     * @Template("ArthurHoaroRssCruncherClientBundle:Testent:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Testent();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('testent_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Testent entity.
     *
     * @param Testent $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Testent $entity)
    {
        $form = $this->createForm(new TestentType(), $entity, array(
            'action' => $this->generateUrl('testent_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Testent entity.
     *
     * @Route("/new", name="testent_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Testent();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Testent entity.
     *
     * @Route("/{id}", name="testent_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ArthurHoaroRssCruncherClientBundle:Client')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Client entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Testent entity.
     *
     * @Route("/{id}/edit", name="testent_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ArthurHoaroRssCruncherClientBundle:Testent')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Testent entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Testent entity.
    *
    * @param Testent $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Testent $entity)
    {
        $form = $this->createForm(new TestentType(), $entity, array(
            'action' => $this->generateUrl('testent_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Testent entity.
     *
     * @Route("/{id}", name="testent_update")
     * @Method("PUT")
     * @Template("ArthurHoaroRssCruncherClientBundle:Testent:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ArthurHoaroRssCruncherClientBundle:Testent')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Testent entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('testent_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Testent entity.
     *
     * @Route("/{id}", name="testent_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ArthurHoaroRssCruncherClientBundle:Testent')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Testent entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('testent'));
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
            ->setAction($this->generateUrl('testent_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
