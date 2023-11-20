@extends('index')

@section('title')
    Home
@endsection
@section('content')
    <!-- Sección de bienvenida -->
    <section class="jumbotron jumbotron-fluid text-center ">
        <div class="container">
            <h1 class="display-4">Bunglebuild</h1>
            <p class="lead mt-2">Construyendo sueños, creando realidades.</p>
            <p class="mt-2 p-4">Lorem ipsum dolor, sit amet consectetur adipisicing elit. In harum suscipit modi quas non
                ex
                repellendus
                iusto delectus dignissimos quo natus vitae maiores repellat dolor dicta, eius minima praesentium
                repudiandae!</p>
        </div>
    </section>
    <!-- Sección de servicios -->
    <section id="servicios" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Nuestros Servicios</h2>
            <div class="d-flex align-content-center ">
                <div class="servicio">
                    <h3>Servicio 1</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Tempora, incidunt quos exercitationem
                        debitis molestiae, sint unde, molestias quaerat nisi assumenda vel repellendus voluptate
                        perspiciatis nam consequuntur in dolore at necessitatibus.</p>
                </div>
                <div class="servicio">
                    <h3>Servicio 2</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam molestias est repudiandae perferendis
                        dolores iusto repellendus rerum, quod sed, laboriosam nemo accusantium nisi corrupti aliquam unde
                        modi qui, ipsum quo! Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda hic magnam
                        autem minus minima non laborum ab fugit cum provident repellat eveniet impedit delectus perspiciatis
                        tempore vel, est suscipit quaerat?</p>
                </div>
                <div class="servicio">
                    <h3>Servicio 3</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem voluptatum voluptate sunt
                        minima vitae, sint architecto excepturi quia esse commodi fugit error at modi blanditiis molestiae?
                        Doloremque reprehenderit illum numquam?</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de proyectos -->
    <section id="proyectos" class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">Nuestros Proyectos</h2>
            <div class="row">
                <!-- Puedes agregar imágenes y descripciones de tus proyectos aquí -->
            </div>
        </div>
    </section>

    <!-- Sección de contacto -->
    <section id="contacto" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Contáctanos</h2>
            <div class="row">
                <div class="col-md-6">
                    <p>Para más información, contáctanos:</p>
                    <ul>
                        <li>Dirección: [Dirección de tu empresa]</li>
                        <li>Teléfono: [Número de teléfono]</li>
                        <li>Email: [Correo electrónico]</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <!-- Agrega un formulario de contacto aquí si es necesario -->
                </div>
            </div>
        </div>
    </section>
@endsection
