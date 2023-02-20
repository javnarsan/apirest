![Portada](https://user-images.githubusercontent.com/81263549/219769312-f011707c-0e1f-4356-9578-7d4defb7be61.jpg)


<a name="top"></a>
# Manual Usuario de la API de Laravel

* [Uso de Postman](#item1)
* [Registro](#item2)
* [Login](#item3)
* [Creacion de noticia](#item4)
* [Eliminacion de noticia](#item5)

<a name="item1"></a>
### Uso de Postman

En la pantalla principal de **Postman** (que puede ser tanto la versión web como la de aplicacion de escritorio) vemos algo así:   
![1](https://user-images.githubusercontent.com/81263549/219881827-a07db69c-4aaf-4cc6-9081-f9b51eea4ab3.jpg)  

Para comenzar a probar los endpoints, en primer lugar debemos dirigirnos al apartado **Workspaces**. Si no tenemos creado ninguno, lo creamos con un par de clicks:  

![2](https://user-images.githubusercontent.com/81263549/219881946-95585156-4f7d-4c83-b8d8-e122f188e812.jpg)  

Una vez dentro de nuestro Workspace, pulsamos en >*Collections -> Create new Collection* para crear una colección en la que podremos guardar nuestros endpoints.  
![3](https://user-images.githubusercontent.com/81263549/220200871-8ee83fe9-5c4d-4240-8ff8-6cb3ae83daae.jpg)  
Pulsamos en el símbolo + que vemos a la derecha de las colecciones y ya podemos empezar a probar los endpoints.


<a name="item2"></a>
### Registro  

El primer endpoint que vamos a probar es el de **Registro**.  
Para probarlo, primero nos dirigimos a **routes/api.php** en nuestro proyecto y comprobamos la ruta y el verbo HTTP que hay que pasarle a Postman.  
En este caso:  
![4](https://user-images.githubusercontent.com/81263549/220202151-08b5e7cf-383c-4c6a-89e3-e26b36ad7974.jpg)  
Después, iniciamos el servidor con el comando **php artisan serve**.  
Una vez tenemos iniciado tanto el servidor como mysql en Xampp, probamos la siguiente ruta en postman:  
![5](https://user-images.githubusercontent.com/81263549/220205741-50e80963-a6f8-4dd0-aa42-af5c943475d7.jpg)  
Como podemos observar abajo, Postman nos lanza un mensaje de **error**:  
![6](https://user-images.githubusercontent.com/81263549/220205821-c5d4cf34-978e-4d03-95a4-ccbee9fe583b.jpg)
Esto significa que el método del controlador está esperando unos parámetros que no le hemos pasado. Para solucionar esto, simplemente nos dirigimos al apartado de **Params** y añadimos los parámetros:  
![7](https://user-images.githubusercontent.com/81263549/220205995-4dfc7dc4-2bbc-4021-88bf-9c30d4e9ad01.jpg)  
Si enviamos esta petición, nos devuelve un código de éxito (200), con un mensaje de éxito: el nombre y un token.
![8](https://user-images.githubusercontent.com/81263549/220206108-0dd0896f-80e0-4f9f-8034-2e9c832fb9cd.jpg)  
En este caso, el token no es necesario.
Para guardar este endpoint, simplemente le damos al botón de Save de arriba a la derecha: 
![image](https://user-images.githubusercontent.com/81263549/220206919-df952415-e131-4693-b674-8db58af74d52.png)
Y ya lo tendríamos en nuestra colección. El nombre por defecto será la ruta del endpoint, pero para más claridad podríamos darle un nombre descriptivo de la funcionalidad que realiza:  
![image](https://user-images.githubusercontent.com/81263549/220206980-677047c7-3d1c-41c7-9dc3-a9ccb78c1951.png)


<a name="item3"></a>
### Login

Para probar el login de nuestra API, buscamos la ruta en **routes/api.php**:  
![image](https://user-images.githubusercontent.com/81263549/220206205-965975a6-a8aa-438d-904c-be14cb237aae.png)
Y, para no tener que estar dependiendo de los mensajes de error como en el punto anterior, nos dirigimos al método del controlador de dicha ruta.
En este caso, en **RegisterController.php**, nos dirigimos al método **login()**.
![image](https://user-images.githubusercontent.com/81263549/220206383-8a77b503-bd82-441b-81a3-54053654e5dd.png)  
Como podemos observar, el método **login()** espera recibir el campo **email** y **password** para logearse. En caso de que las credenciales sean correctas, la respuesta será un mensaje de éxito que incluirá el código 200 y el token generado por este método. En caso contrario, si las credenciales no son correctas, se recibirá un mensaje de error "No estás autorizado" con un código 401. 
Para probar este endpoint, creamos una nueva pestaña en Postman y escribimos su ruta con sus parámetros:  
![image](https://user-images.githubusercontent.com/81263549/220207611-47c4c309-e8f9-4b65-bddf-84d21c402516.png)  
Y la respuesta, tal y como esperábamos, es el mensaje de éxito con el token:  
![image](https://user-images.githubusercontent.com/81263549/220207652-12b06e3c-d2a5-4815-80e8-2d384bc0f81a.png)  
En este caso sí que nos guardamos el token, ya que nos hará falta más adelante.

<a name="item4"></a>
### Creacion de noticia
Lo primero que tenemos que tener en cuenta para este endpoint es que su ruta se encuentra agrupada dentro de un middleware de autenticación:  
![image](https://user-images.githubusercontent.com/81263549/220207813-6629feda-7644-4697-b3fe-5d0b1bf78f8e.png)  
Esto significa que, para poder usar estos métodos del controlador en Postman, vamos a necesitar un token de autenticación. En este caso, el que obtuvimos al logearnos con éxito.
Al ser un controlador resource, solo necesitamos una ruta para realizar el CRUD.
Esta sería **http://127.0.0.1:8000/api/articles** y lo que va a marcar qué método del controlador vamos a utilizar va a ser el verbo HTTP.
Por ejemplo, si llamamos a esa ruta con el verbo POST, crearemos una noticia. Si usamos el verbo PUT, la actualizaremos.
Como queremos crear una noticia, creamos el endpoint en Postman con la ruta mencionada anteriormente y usamos el verbo HTTP Post pasándolo los parámetros que indica el controlador (en este caso, **title**, **image**, **description** y **cicle_id**):  
![image](https://user-images.githubusercontent.com/81263549/220208189-bc09bca6-25e6-44d4-84b0-266bfe83415c.png)
![image](https://user-images.githubusercontent.com/81263549/220208374-69b13f50-e21d-4458-8a38-d37ed2739922.png)  
Si probamos este endpoint, obtendremos este error:  
![image](https://user-images.githubusercontent.com/81263549/220208405-89bbbea6-5cc7-4d18-9f4f-218fd50a7865.png)  
Esto se debe a que, como mencionamos antes, al estar este controlador en el middleware de autenticación, necesita el token para poder ejecutarse.
Lo único que tenemos que hacer es dirigirnos a la pestaña de **Headers** de Postman y añadir lo siguiente:  
![image](https://user-images.githubusercontent.com/81263549/220208557-b02e15b4-01d2-4fe6-8a62-861635b4c3df.png)  
Con estos parámetros, si probamos de nuevo el endpoint, obtendremos un mensaje de éxito con, como respuesta, el objeto que hemos creado:  
![image](https://user-images.githubusercontent.com/81263549/220208621-c744fa96-9127-4942-adb9-61fcc734a383.png)  

<a name="item5"></a>
### Eliminacion de noticia
Para eliminar una noticia, el proceso que debemos seguir es muy similar al de crear una noticia. 
Para hacerlo de una forma más cómoda, simplemente duplicamos la pestaña de **Crear noticia** haciendo click derecho en ella:  
![image](https://user-images.githubusercontent.com/81263549/220208828-06efecbf-39a7-4fed-bb29-fe350a689999.png)  
Y modificamos la ruta para, simplemente, poner el id de la noticia que queremos eliminar despues de **articles**. Quedaría así:  
![image](https://user-images.githubusercontent.com/81263549/220209035-64cc69cb-0423-4259-9efe-92245de14443.png)  
No necesitamos volver a realizar el paso de rellenar los headers de autenticación porque, al haber duplicado la pestaña, se quedan guardados.
Si probamos este endpoint, obtendremos este mensaje de respuesta:  
![image](https://user-images.githubusercontent.com/81263549/220209132-e967005e-86a6-4f70-ace9-3758c1dfab89.png)  
Indicando que, efectivamente, se ha borrado la noticia con el id que le hemos pasado al endpoint.



[Volver](#top)
