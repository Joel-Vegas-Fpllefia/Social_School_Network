# Para extraer los testimonios por ahora tendremos que hacer la siguiente query

```sql
SELECT us.name,us.surname,rl.role,us.picture_profile,op.puntuacion,op.comentario from users us JOIN roles rl ON us.id_role = rl.id_role JOIN opinions op ON op.id_user = us.id_user where rl.role != 'Admin'
```

Lo que estamos haciendo es agrupar las dos tablas mediante el join y filtrar por que en el campo rl.role no este admin, esto lo que realizara vincular todos los registros por el id.role y el registro que tenga en el campo us.role 1 no se mostrara ya que ese campo pertenece a Admin.

Donde seleccionamos a todos menos al admin, luego tendremos que crear una tabal donde se escriba las opiniones de los testimonios y vincular por si estan o no en esa tabla.

# Extraemos las noticias y la cantidad de comentarios en cada noticia

## Query para seccion que mostrara todas las noticias

```sql
SELECT nw.id_news, nw.title, nw.descr,nw.imagen, COUNT(cm.text_comment) AS cantidad_comentarios  FROM news nw JOIN news_relation_comment nrc ON nrc.id_news = nw.id_news JOIN comment cm ON cm.id_comment = nrc.id_comment GROUP BY nw.id_news;
```

Lo que estamos haciendo es mediante la tabla intermedia de relation_comment es extraer la cantidad de comentarios que tiene cada noticia, para ello tenemos que adjuntar las 3 tablas entre ellas y luego agrupar por la noticia y contamos cuantos comentarios tiene relacionados.

## Query para cuando necesitemos filtrar

```sql
SELECT nw.id_news, nw.title, nw.descr,nw.imagen, COUNT(cm.text_comment) AS cantidad_comentarios FROM news nw JOIN news_relation_comment nrc ON nrc.id_news = nw.id_news JOIN comment cm ON cm.id_comment = nrc.id_comment WHERE nw.id_category = '" . $number_section . "' GROUP BY nw.id_news;
```

# Mostrar las 3 ultimas noticias

```sql
SELECT nw.title,nw.descr,nw.imagen,nw.fecha_creacion,ct.category FROM news nw JOIN category ct ON nw.id_category = ct.id_category ORDER BY nw.fecha_creacion DESC LIMIT 3;
```

Ordenamos de forma descendente (de mas nuevo a mas antiguo) por la fecha de  creacion y limitamos el resultado a unicamente 3 filas, realizamos tambien un Join para extraer de que categoria es cada anuncio

# Query para cada noticia

```sql
SELECT nw.title, nw.imagen, nw.content_new, nw.fecha_creacion, COUNT(cm.data_comment) AS cantidad_comments from news nw JOIN category ct ON nw.id_category = ct.id_category JOIN news_relation_comment nrc ON nrc.id_news = nw.id_news JOIN comment cm ON cm.id_comment = nrc.id_comment WHERE nw.id_news = " . $_POST['id_news'] . " GROUP BY nw.id_news
```

# Para extraer los comentarios

```sql
SELECT nw.id_news,cm.id_comment,us.name, us.surname, us.picture_profile, cm.data_comment, cm.text_comment, rl.role FROM news nw JOIN news_relation_comment nrc ON nrc.id_news = nw.id_news JOIN comment cm ON cm.id_comment = nrc.id_comment JOIN users us ON us.id_user = cm.id_user JOIN roles rl ON rl.id_role = us.id_role LEFT JOIN response rp ON rp.id_nrc_parent = nrc.id_nvc where rp.id_response IS NULL AND nw.id_news = " . $_POST['id_news'] . "
```

## Query para extraer las respuestas a los comentarios

```sql
SELECT us.name, us.surname, us.picture_profile, cm.data_comment, cm.text_comment, rl.role FROM news nw JOIN news_relation_comment nrc ON nrc.id_news = nw.id_news JOIN comment cm ON cm.id_comment = nrc.id_comment JOIN users us ON us.id_user = cm.id_user JOIN roles rl ON rl.id_role = us.id_role JOIN response rp ON rp.id_nrc_parent = nrc.id_nvc where nw.id_news = '2' AND rp.id_comment_reply = '1001';
```

# Insertar usuarios

```sql
INSERT into  users (name,surname,mail,picture_profile,password,id_role,id_job) VALUE (?,?,?,?,4,4)
```

# Inicio Session

``` sql
SELECT us.mail,us.name,us.surname,us.password,us.picture_profile,rl.role FROM users us JOIN roles rl ON rl.id_role = us.id_role WHERE us.mail = ?
```

# Cantidad de usuarios en la DB

```sql
select COUNT(us.id_user) from users us;
```

# Cantidad de Noticias en la DB

```sql
SELECT COUNT(nw.id_news) FROM news nw;
```

# Cantidad de mensajes

```sql
SELECT COUNT(nrc.id_nvc) FROM news_relation_comment nrc LEFT JOIN response rp ON rp.id_nrc_parent = nrc.id_nvc WHERE rp.id_nrc_parent IS NULL;
```

# Extraemos las categorias de estado de incidencies
```sql
SELECT st.status FROM status_task st;
```
# Extraer todas las categorias de incidencias 
```
SELECT tk.option_name FROM options_task tk sf
```

# Extraer todas las incidencias 
```sql
SELECT tk.id_task, us.id_user, us.name, us.surname, tk.message, tk.date_task, opt.option_name, stt.status, opt.option_name FROM tasks tk JOIN users us ON us.id_user = tk.id_user JOIN options_task opt ON opt.id_options_task = tk.id_options_support JOIN status_task stt ON stt.id_options_status = tk.status_task;
```

# Extraer todas las noticias 
```sql
SELECT nw.id_news, nw.title, nw.descr, nw.content_new, nw.imagen, nw.fecha_creacion, ct.category FROM news nw JOIN category ct ON nw.id_category = ct.id_category
```

# Extraer categorias de las noticias
```sql
SELECT ct.category FROM category ct where ct.id_category != 5;
```

# Extraer Opiniones 
```sql
SELECT op.id_opinion, us.id_user, us.name, us.surname, op.comentario, op.puntuacion, rl.role FROM opinions op JOIN users us ON op.id_user = us.id_user JOIN roles rl ON rl.id_role = us.id_role;
```

# Extraer los FAQS 
```sql
SELECT 
fq.id_faqs,
fq.title,
fq.text,
sfq.section_faq
FROM
faqs fq
JOIN 
sections_faqs sfq
ON
fq.id_section_faq   = sfq.id_sections_faqs
```

# Extraer Usuarios
```sql
SELECT us.id_user, us.name, us.surname, us.mail, rl.role, jb.job FROM users us JOIN roles rl ON us.id_role = rl.id_role LEFT JOIN jobs jb ON jb.id_job = us.id_job
```

# Extraer categorias de Faqs
```sql
SELECT sf.section_faq FROM sections_faqs sf
```

# Extrar todos los faqs 
```
SELECT fq.title,fq.text FROM faqs fq JOIN sections_faqs sfq ON fq.id_faqs = sfq.id_sections_faqs WHERE sfq.id_sections_faqs = ".$_POST['id_category']
```
# Extraer los reportes realizados 
```
SELECT tk.message, tk.titulo, tk.date_task, opt.option_name, stt.status, rpst.response FROM tasks tk JOIN users us ON us.id_user = tk.id_user JOIN options_task opt ON opt.id_options_task = tk.id_options_support JOIN status_task stt ON stt.id_options_status = tk.status_task JOIN response_task rpst ON rpst.id_task = tk.id_task ;
```

# Extraer los comentarios segun el usuario
```
    SELECT cm.data_comment, cm.text_comment FROM comment cm JOIN users us ON cm.id_user = us.id_user
```