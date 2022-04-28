create table wp_sigsa_ACSPermissaoAcessoSIGSA(
IDpermissao int not null primary key auto_increment,
DSpermissao varchar(50) not null);
create table wp_sigsa_ACSPerfilAcessoSIGSA(
IDperfil int not null primary key auto_increment,
DSperfil varchar(50) not null);

create table wp_sigsa_PerfilPermissaoSIGSA(
IDPerfilpermissao int not null primary key auto_increment,
IDPerfil int not null,
IDPermissao int not null,
 FOREIGN KEY (IDPerfil) REFERENCES wp_sigsa_ACSPerfilAcessoSIGSA(IDPerfil),
 FOREIGN KEY (IDPermissao) REFERENCES wp_sigsa_ACSPermissaoAcessoSIGSA(IDPermissao)
);

