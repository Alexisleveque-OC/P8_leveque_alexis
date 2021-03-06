<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Service\Task\RemoveService;
use App\Service\Task\SaveService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     * @param TaskRepository $taskRepository
     * @param Security $security
     * @return Response
     * @IsGranted ("TASK_LIST")
     */
    public function listTask(TaskRepository $taskRepository, Security  $security)
    {
        $user = $this->getUser();

        $tasks = $taskRepository->findAllTasksToDoByUser($user, $security->isGranted("ROLE_ADMIN"));

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/tasks/done", name="task_done_list")
     * @param TaskRepository $taskRepository
     * @param Security $security
     * @return Response
     * @IsGranted ("TASK_LIST")
     */
    public function listTaskDone(TaskRepository $taskRepository, Security $security)
    {
        $user = $this->getUser();

        $tasks = $taskRepository->findAllTasksDoneByUser($user, $security->isGranted("ROLE_ADMIN"));

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @param Request $request
     * @param SaveService $taskSaveService
     * @return RedirectResponse|Response
     * @IsGranted("TASK_CREATE")
     */
    public function createTask(Request $request, SaveService $taskSaveService)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            $taskSaveService->saveTask($task, $user);

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     * @param Task $task
     * @param Request $request
     * @param SaveService $taskSaveService
     * @return RedirectResponse|Response
     * @IsGranted("TASK_EDIT",subject="task")
     */
    public function editTask(Task $task, Request $request, SaveService $taskSaveService)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {

            $taskSaveService->saveTask($task);

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * @param Task $task
     * @param SaveService $taskSaveService
     * @return RedirectResponse
     * @IsGranted("TASK_EDIT", subject="task")
     */
    public function toggleTask(Task $task,SaveService $taskSaveService)
    {
        $task->toggle(!$task->isDone());

        $taskSaveService->saveTask($task);

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @param Task $task
     * @param RemoveService $taskRemoveService
     * @return RedirectResponse
     * @throws \Exception
     * @IsGranted("TASK_DELETE",subject="task")
     */
    public function deleteTaskAction(Task $task, RemoveService $taskRemoveService)
    {
        $taskRemoveService->removeTask($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
