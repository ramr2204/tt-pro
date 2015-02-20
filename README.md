Instalacion del aplicativo Estampillas-Pro
=========


### Notas

- Luego de realizar la copia de los archivos del repositorio, se debe dar permisos de 777 al directorio donde se cargan archivos ```/uploads```, la omisión de este paso provoca que el aplicativo redireccione al login cuando se vaya a cargar un archivo sin haber dado permisos al directorio.

- En el archivo de configuración **config.php**  ```/application/config/config.php``` se debe especificar al final del string del valor del indice ```$config['application_root']```, el nombre de la carpeta en la que se almacene el aplicativo, ya que los distintos **PDF** que genera el aplicativo utilizan rutas absolutas para cargar las imagenes que utiliza. 

- La aplicacion hace uso de la bases de datos ***estampillas-pro***, las cuales debe estar instalada en el servidor *MySQL* para que la aplicacion pueda funcionar.

- La aplicacion hace uso de un **WEB** SERVICE para actualizar la base de datos local de contratos, para ello utiliza la funcionalidad CURL de php en la cual se especifica una ruta absoluta a dicha **API** y se envía el parametro vige ```http://190.121.133.172:81/siscon/main/modulos/informes/general/contratos.php?vige=".$vigencia```, en caso de que el aplicativo **SISCON** sea movido de servidor o de directorio se deberá modificar dicha ruta en ```estampillas/application/controllers/contratos.php:264``` para garantizar la correcta importación de contratos.

> **(NOTA)** para realizar la importación de contratos desde la base de datos de producción debe ejecutar el aplicativo desde una dirección externa, por ejemplo para pruebas sería ```www.softwareenlanube.net:8182/estampillas-pro```. Si desea importar contratos desde la base de datos de pruebas del servidor **19** debe modificar la ruta establecida para el **WEB SERVICE** por ```http://192.168.77.19/siscon/main/modulos/informes/general/contratos.php?vige=".$vigencia``` de esa manera podrá importar contratos de prueba desde la red interna (puede que no esté actualizada dicha BD). Recuerde dejar la ruta en su estado inicial, apuntando hacia el servidor de producción cuando solicite un **MERGE REQUEST**.



### Pasos de instalacion


- Ejecutar el archivo ***estampillas_pro.sql*** en el servidor *MySQL*.
- Copiar la carpeta ***/estampillas-pro*** en la raiz del servidor apache.


- Ir a la ruta ```application/config/```.
- Eliminar la extensión```.25``` del archivo ```database.php.25``` para trabajar con los datos de conexion en ese servidor de pruebas o eliminar la extensión ```.produccion``` del archivo ```database.php.produccion``` para trabajar en producción. 
- Ir a la ruta ```application/controllers/```.
- Eliminar la extension ```.25``` de los archivos ```generarpdf.php.25``` y  ```qr.php``` para que los codigos **QR** generados en las estampillas direccionen a información contenida en ese Servidor, o eliminar la extension ```.produccion``` de los archivos ```generarpdf.php.produccion``` y  ```qr.php.produccion``` para que los codigos **QR** generados en las estampillas direccionen a información contenida en el Servidor de Producción.

> **(NOTA)** solo debe modificar los archivos referenciados, quedando los archivos de la forma ***ejemplo:*** **primer archivo** ```database.php.25```,  **quedaría** ```database.php``` para que pueda trabajar. Al finalizar los cambios debe modificar nuevamente los archivos para que queden en su estado inicial ***ejemplo:*** **primer archivo** ```database.php```  **quedaría nuevamente** ```database.php.25``` antes de solicitar el merge request para el versionamiento.
