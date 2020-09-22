<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Service\TaskFindService;
use App\Service\TaskRemoveService;
use App\Service\TaskSaveService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     * @param TaskRepository $taskRepository
     * @return Response
     */
    public function listTask(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findAllTasks();

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @param Request $request
     * @param TaskSaveService $taskSaveService
     * @return RedirectResponse|Response
     */
    public function createTask(Request $request, TaskSaveService $taskSaveService)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {

            $taskSaveService->saveTask($task);

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     * @param Task $task
     * @param Request $request
     * @param TaskSaveService $taskSaveService
     * @return RedirectResponse|Response
     */
    public function editTask(Task $task, Request $request, TaskSaveService $taskSaveService)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
            $taskSaveService->saveTask();

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
     * @param TaskSaveService $taskSaveService
     * @return RedirectResponse
     */
    public function toggleTask(Task $task, TaskSaveService $taskSaveService)
    {
        $task->toggle(!$task->isDone());

        $taskSaveService->saveTask($task);

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @param Task $task
     * @param TaskRemoveService $taskRemoveService
     * @return RedirectResponse
     */
    public function deleteTaskAction(Task $task,TaskRemoveService $taskRemoveService)
    {
        $taskRemoveService->removeTask($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
