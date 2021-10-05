DROP TABLE IF EXISTS public.hash;
DROP TABLE IF EXISTS public.usuario;
DROP TABLE IF EXISTS public.tecnologiasporvaga;
DROP TABLE IF EXISTS public.vaga;
DROP TABLE IF EXISTS public.empresa;
DROP TABLE IF EXISTS public.tecnologia;
CREATE TABLE public.usuario (
	"idUsuario" serial NOT NULL,
	login character varying(20) NOT NULL,
	email character varying(100) NOT NULL UNIQUE,
	senha character varying(50) NOT NULL UNIQUE,
	"dataCadastro" timestamp with time zone NOT NULL,
	status boolean NOT NULL default '0',
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_idUsuario" PRIMARY KEY("idUsuario")
) WITH (OIDS = FALSE);
CREATE TABLE public.hash (
	"idHash" SERIAL NOT NULL,
	hash varchar(255) NOT NULL,
	status boolean NOT NULL,
	"dataCadastro" timestamp NOT NULL,
	"FK_idUsuario" integer NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_idHash" PRIMARY KEY("idHash"),
	CONSTRAINT "FK_idUsuario" foreign key ("FK_idUsuario") references public.usuario("idUsuario") ON DELETE NO ACTION
) WITH (OIDS = FALSE);
CREATE TABLE public.empresa (
	idempresa SERIAL NOT NULL,
	razaosocial character varying(80) NOT NULL,
	nomefantasia character varying(80) DEFAULT NULL,
	"CNPJ" character varying(18) DEFAULT NULL UNIQUE,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_idempresa" PRIMARY KEY(idempresa)
) WITH (OIDS = FALSE);
CREATE TABLE public.tecnologia (
	idtecnologia SERIAL PRIMARY KEY,
	tecnologia character varying(45) NOT NULL,
	excluido boolean NOT NULL default '0',
) WITH (OIDS = FALSE);
CREATE TABLE public.vaga (
	idvaga SERIAL NOT NULL,
	titulo character varying(45) NOT NULL,
	descricao text DEFAULT NULL,
	"FK_idempresa" integer NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "PK_idvaga" PRIMARY KEY(idvaga),
	CONSTRAINT "FK_idempresa" foreign key ("FK_idempresa") references public.empresa(idempresa) ON DELETE NO ACTION
) WITH (OIDS = FALSE);
CREATE TABLE public.tecnologiasporvaga (
	"FK_idtecnologia" integer NOT NULL,
	"FK_idvaga" integer NOT NULL,
	excluido boolean NOT NULL default '0',
	CONSTRAINT "FK_idtecnologia" foreign key ("FK_idtecnologia") references public.tecnologia(idtecnologia) ON DELETE NO ACTION,
	CONSTRAINT "FK_idvaga" foreign key ("FK_idvaga") references public.vaga(idvaga) ON DELETE NO ACTION
) WITH (OIDS = FALSE);