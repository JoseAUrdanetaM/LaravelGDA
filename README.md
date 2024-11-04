
# Prueba Técnica GPD

Este es un proyecto desarrollado con el framework Laravel. Este README proporciona información sobre cómo instalar, configurar y utilizar el servicio, así como detalles sobre los servicios y métodos disponibles.

## Tabla de Contenidos

-   Requerimientos Mínimos
-   Instalación
-   Estructura del Proyecto
-   Definición de Servicios
-   Métodos de Servicio
-   Uso
-   Comandos adicionales de Artisan

## Requerimientos Mínimos

Antes de instalar este proyecto, asegúrate de tener los siguientes requerimientos:

-   PHP ^8.2
-   Composer 
-   MySQL
-   Postman (para probar las rutas de la API, opcional)

## Instalación

**1.  Clonar el repositorio:**  

	git clone https://github.com/JoseAUrdanetaM/LaravelGDA
        
  Luego de ello

	cd LaravelGDA	
        
**2.  Instalar dependencias con Composer:**  

    composer install
    
**3.  Configurar el archivo .env**
-   Configura los valores de conexión a la base de datos:  
``DB_CONNECTION=mysql``
``DB_HOST=127.0.0.1``
``DB_PORT=3306``
``DB_DATABASE=mydb``
``DB_USERNAME=root``
``DB_PASSWORD=``

**4.  Generar la clave de aplicación:**  

	php artisan key:generate
    
**5.  Ejecutar las migraciones y seeds**
    
-   Ejecuta las migraciones para crear las tablas en la base de datos:  

		php artisan migrate
	
	Este deberá crear las tablas
	``
	communes,
	customers,
	migrations,
	regions,
	tokens,
	users
	``
    
-   Corre los seeders para poblar la base de datos con datos iniciales:  
		  
		  php artisan db:seed
	 
	 Para crear datos para testing de 
   ``	communes,	customers, regions, users``
    

**6.  Iniciar el servidor:**  

    php artisan serve --port=8000  
    
   Accede al proyecto en [http://localhost:8000](http://localhost:8000)
    

## Estructura del Proyecto

-   app/: Contiene la lógica de la aplicación, incluyendo modelos y controladores.
-   database/: Contiene migraciones, seeders y la base de datos.
-   routes/: Definición de las rutas de la aplicación.

## Definición de Servicios

Este proyecto incluye varios servicios que permiten realizar operaciones sobre las entidades de la aplicación. A continuación se describen los principales servicios:

-   AuthController: Maneja la autenticación de usuarios.
-   RegionController: Gestiona las regiones.
-   CustomerController: Permite realizar operaciones CRUD sobre los clientes.
-   CommuneController: Maneja las comunas.


## Métodos de Servicio

Cada controlador expone una serie de métodos. Aquí tienes una lista de algunos de los métodos más utilizados:

### AuthController

-   login(Request $request): Inicia sesión y devuelve un token de autenticación.

### RegionController

-   index(): Devuelve una lista de todas las regiones.
-   show($id): Devuelve los detalles de una región específica.
-   store(Request $request): Crea una nueva región.
-   destroy($id): Elimina una región existente.

### CustomerController

-   index(): Devuelve una lista de todos los clientes.
-   show($id): Devuelve los detalles de un cliente específico.
-   store(Request $request): Crea un nuevo cliente.
-   destroy($id): Elimina un cliente existente.

### CommuneController

-   index(): Devuelve una lista de todas las comunas.
-   show($id): Devuelve los detalles de una comuna específica.
-   store(Request $request): Crea una nueva comuna.
-   destroy($id): Elimina una comuna existente.

## Uso

**Autenticación**:

-   Ruta para autenticarse:

> email: test@example.com password: password

``POST /api/login``

 Envía las credenciales y obtén un token para acceder a las demás rutas.
-   **Endpoints de la API**:
    
    -   Todos los endpoints están protegidos por el middleware de autenticación y requieren el token de sesión obtenido en el login. 
    
     Customer
    
    `GET /api/customers` - Listar todas los clientes.
    
    `GET /api/customers/{id}` - Obtener detalles de un cliente especifico

    `POST /api/customers` - Crear un nuevo cliente.

    `DELETE /api/customers/{id}` - Eliminar un cliente.

    `GET /api/customers/search` - Obtener datos de cliente según DNI o correo.


     Commune
        -   `GET /api/commune` - Listar todas las comunas.
        -   `GET /api/commune/{id}` - Obtener detalles de una comuna específica
        -   `POST /api/commune` - Crear una nueva comuna.
        -   `DELETE /api/commune/{id}` - Eliminar una comuna.
               
     Region
        -   `GET /api/region` - Listar todas las regiones.
        -   `GET /api/region/{id}` - Obtener detalles de una región específica
        -   `POST /api/region` - Crear una nueva región.
        -   `DELETE /api/region/{id}` - Eliminar una región.

-   **Uso en Postman**:
    
    -   Para simplificar las pruebas, puedes importar la colección de Postman que contiene las rutas configuradas.
    -   ID del workspace de Postman para acceder a la colección: `2eded0a6-241f-4dc1-8214-735d9755476f`
    -   En Postman, ve a **Workspaces** > **Switch Workspace**, busca este ID, y tendrás acceso a las rutas configuradas para probar la API.
## Comandos adicionales de Artisan

-   **Limpiar caché de vistas**:
``php artisan view:clear``
