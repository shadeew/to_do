<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #f7f9fb; }
        .todo-container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08); }
         </style>
</head>
<body>

<div class="todo-container">
    <h2 class="text-center mb-4">To-Do List</h2>

    <form id="add-task-form" class="input-group mb-4">
     <button id="toggle-view" type="button" class="btn btn-outline-info"> Show All Tasks</button>
        <input type="text" id="task-title" class="form-control" placeholder="Project # To Do" required>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
    <ul id="task-list" class="list-group"> </ul>
</div>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});
    $('#toggle-view').click(function () {
    showAll = !showAll;
    $(this).text(showAll ? 'Show Incomplete Tasks Only' : 'Show All Tasks');
    loadTasks();});

    let showAll = false;
function loadTasks() {
    $.get('/tasks', { all: showAll }, function(tasks) {
        let my_task = '';
        tasks.forEach(task => {my_task += 
            `<li class="list-group-item d-flex justify-content-between align-items-center task-item ${task.completed ? 'completed' : ''}" data-id="${task.id}">
                    <div class="d-flex align-items-center">
                   <button class="btn btn-sm btn-outline-${task.completed ? 'success' : 'secondary'} me-2 toggle">  <i class=" ${task.completed ? 'fa-solid fa-square-check' : 'fa-regular fa-square'}"></i></button>
                    <span>${task.title}</span>
                         </div>
                  <button class="btn btn-sm btn-danger delete">
                  <i class="fa-solid fa-trash"></i>
                  </button>
                </li> `; });
    $('#task-list').html(my_task);});
}
    loadTasks();
    $('#add-task-form').submit(function(e) {
        e.preventDefault();
        let title = $('#task-title').val();
           $.post('/tasks', { title }, function() {
            $('#task-title').val('');
         loadTasks();});
    });

    $('#task-list').on('click', '.toggle', function() {
        let id = $(this).closest('li').data('id');
            $.post(`/tasks/${id}/toggle`, {}, function() {
          loadTasks();});
    });

    $('#task-list').on('click', '.delete', function() {
    let id = $(this).closest('li').data('id');

    if (confirm(" Are you sure you want to delete this task?")) {
        $.ajax({url: `/tasks/${id}`,
        type: 'DELETE',
         success: function() {
        loadTasks();
            } });


    }
});
</script>

</body>
</html>