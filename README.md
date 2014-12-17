Instalacion del aplicativo Estampillas-Pro
=========


### Notas

- Luego de realizar la copia de los archivos del repositorio, se debe dar permisos de 777 al directorio donde se cargan archivos ```/uploads```, la omisión de este paso provoca que el aplicativo redireccione al login cuando se vaya a cargar un archivo sin haber dado permisos al directorio.

- En el archivo de configuración **config.php**  ```/application/config/config.php``` se debe especificar al final del string del valor del indice ```$config['application_root']```, el nombre de la carpeta en la que se almacene el aplicativo, ya que los distintos **PDF** que genera el aplicativo utilizan rutas absolutas para cargar las imagenes que utiliza. 

- La aplicacion hace uso de la bases de datos ***estampillas_pro***, las cuales debe estar instalada en el servidor *MySQL* para que la aplicacion pueda funcionar.

- La aplicacion hace uso de un **WEB** SERVICE para actualizar la base de datos local de contratos, para ello utiliza la funcionalidad CURL de php en la cual se especifica una ruta absoluta a dicha **API** y se envía el parametro vige ```http://192.168.77.19/siscon/main/modulos/informes/general/contratos.php?vige=".$vigencia```, en caso de que el aplicativo **SISCON** sea movido de servidor o de directorio se deberá modificar dicha ruta en ```estampillas/application/controllers/contratos.php:264``` para garantizar la correcta importación de contratos.


### Pasos de instalacion


- Ejecutar el archivo ***estampillas_pro.sql*** en el servidor *MySQL*.
- Copiar la carpeta ***/estampillas-pro*** en la raiz del servidor apache.


- Ir a la ruta ```application/config/```.
- Añadir la extension ```.produccion``` al archivo ```database.php``` para deshabilitar los datos de conexión de producción y trabajar en pruebas.
- Eliminar la extension```.25``` del archivo ```database.php.25``` para trabajar con los datos de conexion en ese servidor.

> **(NOTA)** solo debe modificar esos dos archivos, quedando los archivos de la forma **primer archivo** ```database.php.produccion``` y  **segundo archivo** ```database.php``` para que pueda trabajar. Al finalizar los cambios debe modificar nuevamente los archivos para que queden en su estado inicial **primer archivo** ```database.php``` y  **segundo archivo** ```database.php.25``` antes de solicitar el merge request para el versionamiento.

