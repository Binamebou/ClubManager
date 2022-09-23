CREATE TABLE myclub_member
(
    ID           int(10) primary key auto_increment,
    LastName     varchar(120) not null,
    FirstName    varchar(120) not null,
    Login        varchar(120) not null,
    MobileNumber varchar(120),
    Email        varchar(120) not null,
    Password     varchar(120) not null,
    LastUpdate   timestamp DEFAULT current_timestamp(),
    Address      varchar(120),
    PostalCode   varchar(10),
    City         varchar(120),
    Country      varchar(120),
    BirthDate    date
);

-- Create a first member with default credential : Username: admin  Password: Test@123
INSERT INTO `myclub_member` (`ID`, `LastName`, `FirstName`, `Login`, `MobileNumber`, `Email`, `Password`,
                             `Address`, `PostalCode`, `City`, `Country`, `BirthDate`)
VALUES (1, 'Doe', 'John', 'admin', '+32123456', 'john.doe@gmail.com', 'f925916e2754e5e03f75dd58a5733251',
           , 'Place Saint Lambert 1', '4000', 'Liège', 'Belgique', '1980-01-01');

CREATE TABLE `myclub_rights`
(
    `member_id` int(11) primary key NOT NULL,
    `role_id`   varchar(20) primary key NOT NULL
);

INSERT INTO `myclub_rights` (`member_id`, `role_id`)
VALUES (1, 'ADMIN'),
       (1, 'USER');

CREATE TABLE `myclub_roles`
(
    `role_name`   varchar(20) primary key NOT NULL,
    `description` varchar(120) DEFAULT NULL
);

INSERT INTO `myclub_roles` (`role_name`, `description`)
VALUES ('ADMIN', "A tous les droits"),
       ('MAILING', "Permet d'envoyer des mails aux membres"),
       ('MANAGER', "Permet de gérer les membres"),
       ('USER', "Permet de se connecter à l'application");
