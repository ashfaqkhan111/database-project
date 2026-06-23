CREATE database digital_library;
use digital_library;

use digital_library;

create table authors (
author_id int auto_increment primary key,
author_name varchar(100) not null,
country varchar(50) not null
);

create table categories (
category_id int auto_increment primary key,
category_name varchar(100) not null
);

create table publishers (
publisher_id int auto_increment primary key,
publisher_name varchar(100) not null,
address varchar(255) not null
);

create table books (
book_id int auto_increment primary key,
title varchar(200) not null,
isbn varchar(20) not null,
publication_year year not null,
available_copies int default 0,
author_id int,
category_id int,
publisher_id int,
foreign key (author_id) references authors(author_id),
foreign key (category_id) references categories(category_id),
foreign key (publisher_id) references publishers(publisher_id)  
);

create table members(
member_id int auto_increment primary key,
member_name varchar(100) not null,
email varchar(100),
phone varchar(20),
address varchar(255),
gender enum('Male','Female'),
registration_date date not null
);

create table librarians (
librarian_id int auto_increment primary key,
librarian_name varchar(100) not null,
password varchar(100) not null,
librarian_code varchar(20) unique,
email varchar(100)
);



create table borrowings (
borrow_id int auto_increment primary key,
member_id int,
book_id int,
librarian_id int,
borrow_date date not null,
due_date date not null,
return_date date not null,
status varchar(20),
foreign key (member_id) references members(member_id),
foreign key (book_id) references books(book_id),
foreign key (librarian_id) references librarians(librarian_id)
);

create table fines (
fine_id int auto_increment primary key,
borrow_id int unique,
amount decimal(10,2) default 0 not null,
paid_status varchar(20),
foreign key (borrow_id) references borrowings(borrow_id)
);




select
b.book_id,
b.title,
b.available_copies,
a.author_id,
c.category_name,
p.publisher_name
from books b
inner join authors a
on b.author_id = a.author_id
inner join categories c
on b.category_id = c.category_id
inner join publishers p
on b.publisher_id = p.publisher_id;

select
br.borrow_id,
m.member_name,
b.title,
br.borrow_date,
br.due_date,
br.return_date,
br.status,
f.amount,
f.paid_status
from borrowings br
inner join members m
on br.member_id = m.member_id
inner join books b
on br.book_id = b.book_id
left join fines f
on br.borrow_id = f.borrow_id;

ALTER TABLE books
ADD status ENUM('Active','Inactive')
NOT NULL DEFAULT 'Active';

alter table members add column member_code varchar(10) unique;




