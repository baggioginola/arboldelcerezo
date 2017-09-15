create database arboldelcerezo;

use arboldelcerezo;

create table usuario(id int(11) not null primary key auto_increment,nombre varchar(200) not null,
apellidos varchar(200) not null, email varchar(200) not null, password varchar(200) not null, status bool,nivel int(5),
fecha_alta timestamp,fecha_modifica timestamp);

insert into usuario(nombre, apellidos,email,password,status,nivel,fecha_alta,fecha_modifica)
values('Mario','Cuevas','mariocue@herbalife.com',md5('4rb0112345'),true,0,now(),now());

create table categoria(id int(11) not null primary key auto_increment, nombre varchar(200) not null, status bool, fecha_alta timestamp,
fecha_modifica timestamp);

create table producto(id int(11) not null primary key auto_increment, nombre varchar(200), descripcion text, precio decimal(10,2),status bool,fecha_alta timestamp,
fecha_modifica timestamp, id_categoria int(11), foreign key(id_categoria) references categoria(id));

create table cart(id int(11) not null primary key auto_increment,variable_session varchar(250),fecha timestamp);
create table cart_productos(id int(11) not null primary key auto_increment, id_cart int(11),id_producto int(11),
foreign key(id_cart) references cart(id), foreign key(id_producto) references producto(id));

