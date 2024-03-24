function loadBlogs() {
    // Esta función carga los blogs desde el servidor y los muestra en #blogs-container

    $.ajax({
        url: 'get_blogs.php',  
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Limpiamos el contenedor
            $('#blogs-container').empty();

            if (data.length > 0) {
                // Mostramos cada blog en el contenedor
                data.forEach(function(blog) {
                    var blogHtml = `
                        <div class='blog-container' data-blog-id='${blog.id}'>
                            <h2>${blog.titulo}</h2>
                            <p><strong>Descripción Breve:</strong> ${blog.descripcion_breve}</p>
                            <p><strong>Descripción:</strong> ${blog.descripcion}</p>
                            <p><strong>Imagen:</strong> <img src='${blog.imagen}' alt='Imagen del Blog'></p>
                            <p><strong>Usuario:</strong> ${blog.usuario_nombre}</p>
                            <p><strong>Fecha:</strong> ${blog.fecha}</p>
                            <button class='edit-button' data-blog-id='${blog.id}'>Editar</button>
                            <button class='delete-button' data-blog-id='${blog.id}'>Borrar</button>
                        </div>
                    `;
                    $('#blogs-container').append(blogHtml);
                });
            } else {
                // Mostramos un mensaje si no hay blogs
                $('#blogs-container').html('<p>No hay blogs para mostrar.</p>');
            }
        },
        error: function(error) {
            console.error('Error al cargar los blogs:', error);
        }
    });
}

// Llamamos a la función para cargar los blogs cuando la página se carga
$(document).ready(function() {
    loadBlogs();
});

// Agregar event listeners para editar y borrar blogs
$('#blogs-container').on('click', '.edit-button', function() {
    var blogId = $(this).data('blog-id');
    // Puedes redirigir al formulario de edición o mostrar un modal, dependiendo de tus necesidades
    console.log('Editar blog con ID:', blogId);
});

$('#blogs-container').on('click', '.delete-button', function() {
    var blogId = $(this).data('blog-id');
    // Mostrar una confirmación antes de borrar
    var confirmDelete = confirm('¿Estás seguro de que quieres borrar este blog?');
    
    if (confirmDelete) {
        // Llamada AJAX para borrar el blog
        $.ajax({
            url: 'delete_blog.php',  // Cambia 'delete_blog.php' al nombre de tu script que borra el blog desde la base de datos
            type: 'POST',
            data: { id: blogId },
            success: function(response) {
                console.log(response);
                // Recargar los blogs después de borrar uno
                loadBlogs();
            },
            error: function(error) {
                console.error('Error al borrar el blog:', error);
            }
        });
    }
});
// Agregar event listeners para editar y borrar blogs
$('#blogs-container').on('click', '.edit-button, .delete-button', function() {
    var blogId = $(this).data('blog-id');

    if ($(this).hasClass('edit-button')) {
        // Mostrar el modal de edición y cargar datos del blog
        $('#edit-blog-modal').show();

        // Obtener los datos del blog para mostrar en el modal
        $.ajax({
            url: 'get_blog.php',
            type: 'GET',
            data: { id: blogId },
            dataType: 'json',
            success: function(data) {
                $('#new-title').val(data.titulo);
                $('#new-description').val(data.descripcion);
                $('#edit-blog-form').data('blog-id', blogId);
            },
            error: function(error) {
                console.error('Error al obtener datos del blog:', error);
            }
        });
    } else if ($(this).hasClass('delete-button')) {
        // Mostrar una confirmación antes de borrar
        var confirmDelete = confirm('¿Estás seguro de que quieres borrar este blog?');

        if (confirmDelete) {
            // Llamada AJAX para borrar el blog
            $.ajax({
                url: 'delete_blog.php',
                type: 'POST',
                data: { id: blogId },
                success: function(response) {
                    console.log(response);
                    // Recargar los blogs después de borrar uno
                    loadBlogs();
                },
                error: function(error) {
                    console.error('Error al borrar el blog:', error);
                }
            });
        }
    }
});

// Agregar event listener para cerrar el modal de edición
$('.close').click(function() {
    $('#edit-blog-modal').hide();
});

// Agregar event listener para enviar el formulario de edición
$('#edit-blog-form').submit(function (event) {
    event.preventDefault();
    var blogId = $(this).data('blog-id');
    var newTitle = $('#new-title').val();
    var newDescription = $('#new-description').val();

    // Llamada fetch para actualizar el blog
    fetch('update_blog.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: blogId,
            new_title: newTitle,
            new_description: newDescription,
        }),
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Recargar los blogs después de actualizar uno
            loadBlogs();
            $('#edit-blog-modal').hide();
        })
        .catch(error => {
            console.error('Error al actualizar el blog:', error);
        });
});
function loadBlogs() {
    // Esta función carga los blogs desde el servidor y los muestra en #blogs-container

    $.ajax({
        url: 'get_blogs.php',  
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Limpiamos el contenedor
            $('#blogs-container').empty();

            if (data.length > 0) {
                // Mostramos cada blog en el contenedor
                data.forEach(function(blog) {
                    // Asegúrate de que la ruta de la imagen sea correcta
                    var imagePath = blog.imagen ? blog.imagen : 'ruta_por_defecto_si_no_hay_imagen.jpg';

                    var blogHtml = `
                        <div class='card' style='width: 18rem; data-blog-id='${blog.id}'>
                        <p><img src='${blog.imagen}' alt='Imagen del Blog'></p>
                            <h2>${blog.titulo}</h2>
                            <p><strong>Descripción Breve:</strong> ${blog.descripcion_breve}</p>
                            <p><strong>Descripción:</strong> ${blog.descripcion}</p>
                            
                            <p><strong>Usuario:</strong> ${blog.usuario_nombre}</p>
                            <p><strong>Fecha:</strong> ${blog.fecha}</p>
                            <button class='edit-button' data-blog-id='${blog.id}'>Editar</button>
                            <button class='delete-button' data-blog-id='${blog.id}'>Borrar</button>
                        </div>
                        
                       
                    `;
                    $('#blogs-container').append(blogHtml);
                });
            } else {
                // Mostramos un mensaje si no hay blogs
                $('#blogs-container').html('<p>No hay blogs para mostrar.</p>');
            }
        },
        error: function(error) {
            console.error('Error al cargar los blogs:', error);
        }
    });
}

