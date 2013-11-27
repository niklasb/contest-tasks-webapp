drop table if exists users;
drop table if exists tasks;
drop table if exists user_tasks;
create table users (
  name varchar(50) not null primary key,
  hash varchar(100) not null
);
create table tasks (
  id int not null auto_increment primary key,
  name varchar(100) not null,
  url varchar(100) not null,
  tags text not null,
  owner varchar(50) not null
);
create table user_tasks (
  user_name varchar(50) not null,
  task_id int not null
);
