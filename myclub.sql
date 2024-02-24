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
    BirthDate    date,
    RGPD         tinyint(1),
    Mailing      tinyint(1)
);

-- Create a first member with default credential : Username: admin  Password: Test@123
INSERT INTO `myclub_member` (`ID`, `LastName`, `FirstName`, `Login`, `MobileNumber`, `Email`, `Password`,
                             `Address`, `PostalCode`, `City`, `Country`, `BirthDate`, RGPD, mailing)
VALUES (1, 'Doe', 'John', 'admin', '+32123456', 'john.doe@gmail.com', 'f925916e2754e5e03f75dd58a5733251',
           'Place Saint Lambert 1', '4000', 'Liège', 'Belgique', '1980-01-01', 1, 0);

CREATE TABLE `myclub_rights`
(
    `member_id` int(11) NOT NULL,
    `role_id`   varchar(20) NOT NULL
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
       ('USER', "Permet de se connecter à l'application"),
       ('INSTRUCTOR', "Permet de gérer les brevets des élèves");



create table myclub_constants
(
    constant_name  varchar(50)   not null
        primary key,
    constant_value varchar(1000) not null
);

INSERT INTO myclub_constants (constant_name, constant_value)
VALUES ("SITE_NAME","My club manager"),
       ("PAGE_TITLE","My club manager"),
       ("MAIL_FROM","Mon club <monclub@mondomaine.com>"),
       ("MAIL_RESPOND_TO","no-reply@mondomaine.com"),
       ("SIDEBAR_HOME_NAME","My Club"),
       ("FOOTER_COPYRIGHT","My club @2022"),
       ("MAIL_DEFAULT_CONTENT","<br /><br /><br /><p style='font-size: 10px;'>Vous pouvez gérez vos préférences de communication pour ne plus recevoir de mails en vous connectant à <a href=''https://my.club.com''>My Club</a> </p>"),
       ("SITE_URL","https://my.club.com"),
       ("MAIL_NEW_MEMBER_SUBJECT", "Votre compte My Club a été créé"),
       ("MAIL_NEW_MEMBER_FOOTER", "<p>Au plaisir de vous revoir</p>"),
       ("MAIL_NEW_PASSWORD_SUBJECT", "Votre compte My Club a été créé"),
       ("MAIL_NEW_PASWORD_FOOTER", "<p>Au plaisir de vous revoir</p>");

CREATE TABLE `myclub_certificates`
(
    ID          int(10) primary key auto_increment,
    MemberId    int(10) not null,
    Label       varchar(120),
    Recto       varchar(256),
    Verso       varchar(256)
);

CREATE TABLE myclub_membership
(
    ID       int(10) primary key auto_increment,
    MemberId int(10) not null,
    Year     int(4) not null,
    Type     varchar(20)
);

ALTER TABLE myclub_membership ADD UNIQUE year_Member_unique (MemberId, Year);

CREATE TABLE myclub_documents
(
    ID          int(10) primary key auto_increment,
    MemberId    int(10) not null,
    Type        varchar(20) not null,
    ValidFrom   date,
    ValidTo     date,
    Path        varchar(256),
    Comment     text
);

ALTER TABLE myclub_member ADD COLUMN active tinyint(1) default 1;
ALTER TABLE myclub_certificates MODIFY Recto text;
ALTER TABLE myclub_certificates MODIFY Verso text;

ALTER TABLE myclub_member ADD COLUMN ArrivalDate date;
ALTER TABLE myclub_member ADD COLUMN MemberType varchar(20);
ALTER TABLE myclub_member ADD COLUMN HighestCertificate varchar(100);
