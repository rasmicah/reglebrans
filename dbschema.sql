CREATE TABLE product(product_id int PRIMARY KEY AUTO_INCREMENT,
product_name varchar(50),
description varchar(256),
category_id int REFERENCES category(category_id),
price real
);

CREATE TABLE product_additional_info(product_id int REFERENCES product(product_id),
info varchar(50),
value varchar(50),
PRIMARY KEY (product_id, info)
);

CREATE TABLE order(order_id int PRIMARY KEY AUTO_INCREMENT,
customer_id int REFERENCES customer(customer_id),
order_date date,
cost real,
status varchar(11)
);

CREATE TABLE ordered_product(order_id int REFERENCES order(order_id),
product_id int REFERENCES product(product_id),
quantity int
PRIMARY KEY (order_id, product_id)
);

CREATE TABLE user(user_id int PRIMARY KEY AUTO_INCREMENT,
username varchar(30),
password char(68),
password_reset_string varchar(68),
password_request_time date,
password_request_window int,
email varchar(30),
user_type varchar(11),
status varchar(11),
last_login date,
login_attempt int,
last_attempt date,
login_window int,
is_locked_out char(1)
);

CREATE TABLE customer(customer_id, int PRIMARY KEY REFERENCES user(user_id),
first_name varchar(30),
last_name varchar(30),
dob date
);

CREATE TABLE administrator(admin_id, int PRIMARY KEY REFERENCES user(user_id),
first_name varchar(30),
last_name varchar(30),
);

CREATE TABLE credit_card(customer_id int REFERENCES customer(customer_id),
card_type varchar(16),
card_number varchar(32),
expiry_date date
);

CREATE TABLE address(customer_id int REFERENCES customer(customer_id),
address1 varchar(30),
address2 varchar(30),
city varchar(30),
state varchar(3),
zip_code varchar(10),
country varchar(25)
);

CREATE TABLE payment(order_id int PRIMARY KEY REFERENCES order(order_id),
payment_date date,
amount real,
balance real,
card_id int REFERENCES credit_card(card_id)
);