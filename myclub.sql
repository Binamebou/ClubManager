CREATE TABLE myclub_admin (
                              ID int(10) primary key auto_increment,
                              LastName varchar(120) not null ,
                              FirstName varchar(120) not null ,
                              Login varchar(120) not null ,
                              MobileNumber varchar(120),
                              Email varchar(120) not null ,
                              Password varchar(120) not null,
                              SuperAdmin boolean default false,
                              LastUpdate timestamp DEFAULT current_timestamp()
);

--  Create a first admin with default credential : Username: admin  Password: Test@123
INSERT INTO myclub_admin (LastName, FirstName, Login, Email, Password, SuperAdmin) VALUES
    ('Admin', 'Admin', 'admin', 'admin@gmail.com', 'f925916e2754e5e03f75dd58a5733251', true);

CREATE TABLE myclub_member (
                              ID int(10) primary key auto_increment,
                              LastName varchar(120) not null ,
                              FirstName varchar(120) not null ,
                              Login varchar(120) not null ,
                              MobileNumber varchar(120),
                              Email varchar(120) not null ,
                              Password varchar(120) not null,
                              LastUpdate timestamp DEFAULT current_timestamp(),
                              Address varchar(120),
                              PostalCode varchar(10),
                              City varchar(120),
                              Country varchar(120),
                              BirthDate date
);
