DROP TABLE IF EXISTS public.hash;
DROP TABLE IF EXISTS public.permissaousuario;
DROP TABLE IF EXISTS public.usuario;
DROP TABLE IF EXISTS public.permissaoperfil;
DROP TABLE IF EXISTS public.perfil;
DROP TABLE IF EXISTS public.tecnologiasporvaga;
DROP TABLE IF EXISTS public.vaga;
DROP TABLE IF EXISTS public.empresa;
DROP TABLE IF EXISTS public.tecnologia;
CREATE TABLE public.perfil (
	"idPerfil" SERIAL NOT NULL,
	nome character varying(20) NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_perfil" PRIMARY KEY("idPerfil")
) WITH (OIDS = FALSE);
CREATE TABLE public.usuario (
	"idUsuario" serial NOT NULL,
	login character varying(20) NOT NULL UNIQUE,
	email character varying(100) NOT NULL UNIQUE,
	senha character varying(50) NOT NULL,
	"dataCadastro" timestamp NOT NULL,
	status boolean NOT NULL default '0',
	"FK_idPerfil" integer NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_usuario" PRIMARY KEY("idUsuario"),
	CONSTRAINT "FK_perfil" foreign key ("FK_idPerfil") references public.perfil("idPerfil") ON DELETE NO ACTION
) WITH (OIDS = FALSE);
CREATE TABLE public.permissaousuario (
	"idPermissaoUsuario" SERIAL NOT NULL,
	nome character varying(20) NOT NULL,
	"tipoPermissao" integer NOT NULL,
	"FK_idUsuario" integer NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_permissaousuario" PRIMARY KEY("idPermissaoUsuario"),
	CONSTRAINT "FK_usuario" foreign key ("FK_idUsuario") references public.usuario("idUsuario") ON DELETE NO ACTION
) WITH (OIDS = FALSE);
CREATE TABLE public.permissaoperfil (
	"idPermissaoPerfil" SERIAL NOT NULL,
	nome character varying(20) NOT NULL,
	"tipoPermissao" integer NOT NULL,
	"FK_idPerfil" integer NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_permissaoperfil" PRIMARY KEY("idPermissaoPerfil"),
	CONSTRAINT "FK_perfil" foreign key ("FK_idPerfil") references public.perfil("idPerfil") ON DELETE NO ACTION
) WITH (OIDS = FALSE);
CREATE TABLE public.hash (
	"idHash" SERIAL NOT NULL,
	hash varchar(255) NOT NULL,
	status boolean NOT NULL,
	"dataCadastro" timestamp NOT NULL,
	"FK_idUsuario" integer NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_hash" PRIMARY KEY("idHash"),
	CONSTRAINT "FK_usuario" foreign key ("FK_idUsuario") references public.usuario("idUsuario") ON DELETE NO ACTION
) WITH (OIDS = FALSE);
CREATE TABLE public.empresa (
	idempresa SERIAL NOT NULL,
	razaosocial character varying(80) NOT NULL,
	nomefantasia character varying(80) DEFAULT NULL,
	"CNPJ" character varying(18) DEFAULT NULL UNIQUE,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_empresa" PRIMARY KEY(idempresa)
) WITH (OIDS = FALSE);
CREATE TABLE public.tecnologia (
	idtecnologia SERIAL NOT NULL,
	tecnologia character varying(45) NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_tecnologia" PRIMARY KEY(idtecnologia)
) WITH (OIDS = FALSE);
CREATE TABLE public.vaga (
	idvaga SERIAL NOT NULL,
	titulo character varying(45) NOT NULL,
	descricao text DEFAULT NULL,
	"FK_idempresa" integer NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_vaga" PRIMARY KEY(idvaga),
	CONSTRAINT "FK_empresa" foreign key ("FK_idempresa") references public.empresa(idempresa) ON DELETE NO ACTION
) WITH (OIDS = FALSE);
CREATE TABLE public.tecnologiasporvaga (
	"FK_idtecnologia" integer NOT NULL,
	"FK_idvaga" integer NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "FK_tecnologia" foreign key ("FK_idtecnologia") references public.tecnologia(idtecnologia) ON DELETE NO ACTION,
	CONSTRAINT "FK_vaga" foreign key ("FK_idvaga") references public.vaga(idvaga) ON DELETE NO ACTION
) WITH (OIDS = FALSE);
INSERT INTO public.perfil (nome)
values ("Administrador");