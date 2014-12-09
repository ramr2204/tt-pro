Instalacion del aplicativo Estampillas-Pro
=========


### Notas

- Luego de realizar la copia de los archivos del repositorio, se debe dar permisos de 777 al directorio donde se cargan archivos ```/uploads```, la omisión de este paso provoca que el aplicativo redireccione al login cuando se vaya a cargar un archivo sin haber dado permisos al directorio.

- En el controlador **generarpdf.php**  ```/application/controllers/generarpdf.php``` se debe especificar en la variable rep, el nombre de la carpeta en la que se almacene el aplicativo, ya que los distintos **PDF** que genera el aplicativo utilizan rutas absolutas para cargar las imagenes que utiliza. 

- La aplicacion hace uso de la bases de datos ***estampillas_pro***, las cuales debe estar instalada en el servidor *MySQL* para que la aplicacion pueda funcionar.


### Pasos de instalacion


- Ejecutar el archivo ***estampillas_pro.sql*** en el servidor *MySQL*.
- Copiar la carpeta ***/estampillas-pro*** en la raiz del servidor apache.


- Ir a la ruta ```application/config/```.
- Añadir la extension ```.produccion``` al archivo ```database.php``` para deshabilitar los datos de conexión de producción y trabajar en pruebas.
- Eliminar la extension```.25``` del archivo ```database.php.25``` para trabajar con los datos de conexion en ese servidor.

> **(NOTA)** solo debe modificar esos dos archivos, quedando los archivos de la forma **primer archivo** ```database.php.produccion``` y  **segundo archivo** ```database.php``` para que pueda trabajar. Al finalizar los cambios debe modificar nuevamente los archivos para que queden en su estado inicial **primer archivo** ```database.php``` y  **segundo archivo** ```database.php.25``` antes de solicitar el merge request para el versionamiento.

