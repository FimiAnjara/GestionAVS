CREATE TABLE fournisseur(
   id_fournisseur VARCHAR(50) ,
   nom VARCHAR(250)  NOT NULL,
   lieux VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_fournisseur)
);

CREATE TABLE Client(
   id_client VARCHAR(50) ,
   nom VARCHAR(250)  NOT NULL,
   PRIMARY KEY(id_client)
);

CREATE TABLE proforma(
   id_proforma VARCHAR(50) ,
   date_ DATE NOT NULL,
   validite INTEGER NOT NULL,
   id_client VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_proforma),
   FOREIGN KEY(id_client) REFERENCES Client(id_client)
);

CREATE TABLE unite(
   id_unite VARCHAR(50) ,
   libelle VARCHAR(150)  NOT NULL,
   PRIMARY KEY(id_unite)
);

CREATE TABLE caisse(
   id_caisse VARCHAR(50) ,
   montant NUMERIC(15,2)   NOT NULL,
   PRIMARY KEY(id_caisse)
);

CREATE TABLE mvt_caisse(
   id_mvt_caisse VARCHAR(50) ,
   origine VARCHAR(50)  NOT NULL,
   debit NUMERIC(15,2)   NOT NULL,
   credit NUMERIC(15,2)   NOT NULL,
   description TEXT,
   date_ TIMESTAMP NOT NULL,
   id_caisse VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_mvt_caisse),
   FOREIGN KEY(id_caisse) REFERENCES caisse(id_caisse)
);

CREATE TABLE proformaFournisseur(
   id_proformaFournisseur VARCHAR(50) ,
   date_ TIMESTAMP NOT NULL,
   etat INTEGER NOT NULL,
   id_fournisseur VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_proformaFournisseur),
   FOREIGN KEY(id_fournisseur) REFERENCES fournisseur(id_fournisseur)
);

CREATE TABLE role(
   id_role VARCHAR(50) ,
   libelle VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_role)
);

CREATE TABLE departement(
   id_departement VARCHAR(50) ,
   libelle VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_departement)
);

CREATE TABLE methodeStock(
   id_methodeStock VARCHAR(50) ,
   libelle VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_methodeStock)
);

CREATE TABLE groupe(
   id_groupe VARCHAR(50) ,
   libelle VARCHAR(150)  NOT NULL,
   PRIMARY KEY(id_groupe)
);

CREATE TABLE entite(
   id_entite VARCHAR(50) ,
   libelle VARCHAR(150)  NOT NULL,
   id_groupe VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_entite),
   FOREIGN KEY(id_groupe) REFERENCES groupe(id_groupe)
);

CREATE TABLE site(
   id_site VARCHAR(50) ,
   libelle VARCHAR(150)  NOT NULL,
   id_entite VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_site),
   FOREIGN KEY(id_entite) REFERENCES entite(id_entite)
);

CREATE TABLE categorie(
   id_categorie VARCHAR(50) ,
   libelle VARCHAR(250)  NOT NULL,
   id_methodeStock VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_categorie),
   FOREIGN KEY(id_methodeStock) REFERENCES methodeStock(id_methodeStock)
);

CREATE TABLE magasin(
   id_magasin VARCHAR(50) ,
   lieux VARCHAR(250)  NOT NULL,
   id_site VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_magasin),
   FOREIGN KEY(id_site) REFERENCES site(id_site)
);

CREATE TABLE utilisateur(
   id_utilisateur VARCHAR(50) ,
   email VARCHAR(50)  NOT NULL,
   mdp VARCHAR(250)  NOT NULL,
   id_departement VARCHAR(50)  NOT NULL,
   id_role VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_utilisateur),
   UNIQUE(email),
   FOREIGN KEY(id_departement) REFERENCES departement(id_departement),
   FOREIGN KEY(id_role) REFERENCES role(id_role)
);

CREATE TABLE article(
   id_article VARCHAR(150) ,
   nom VARCHAR(250)  NOT NULL,
   stock NUMERIC(15,2)   NOT NULL,
   id_unite VARCHAR(50)  NOT NULL,
   id_categorie VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_article),
   FOREIGN KEY(id_unite) REFERENCES unite(id_unite),
   FOREIGN KEY(id_categorie) REFERENCES categorie(id_categorie)
);

CREATE TABLE commande(
   id_commande VARCHAR(50) ,
   date_ TIMESTAMP NOT NULL,
   etat INTEGER NOT NULL,
   id_utilisateur VARCHAR(50)  NOT NULL,
   id_client VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_commande),
   FOREIGN KEY(id_utilisateur) REFERENCES utilisateur(id_utilisateur),
   FOREIGN KEY(id_client) REFERENCES Client(id_client)
);

CREATE TABLE proformaFille(
   id_proformaFille VARCHAR(50) ,
   quantite INTEGER NOT NULL,
   id_unite VARCHAR(50)  NOT NULL,
   id_article VARCHAR(150)  NOT NULL,
   id_proforma VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_proformaFille),
   FOREIGN KEY(id_unite) REFERENCES unite(id_unite),
   FOREIGN KEY(id_article) REFERENCES article(id_article),
   FOREIGN KEY(id_proforma) REFERENCES proforma(id_proforma)
);

CREATE TABLE livraison(
   id_livraison VARCHAR(50) ,
   date_ TIMESTAMP NOT NULL,
   etat INTEGER NOT NULL,
   id_commande VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_livraison),
   FOREIGN KEY(id_commande) REFERENCES commande(id_commande)
);

CREATE TABLE articleFille(
   id_articleFille VARCHAR(50) ,
   prix NUMERIC(15,2)   NOT NULL,
   date_ TIMESTAMP NOT NULL,
   quantite NUMERIC(15,2)   NOT NULL,
   id_unite VARCHAR(50) ,
   id_article VARCHAR(150)  NOT NULL,
   PRIMARY KEY(id_articleFille),
   FOREIGN KEY(id_unite) REFERENCES unite(id_unite),
   FOREIGN KEY(id_article) REFERENCES article(id_article)
);

CREATE TABLE commandeFille(
   id_commandeFille VARCHAR(50) ,
   quantite INTEGER NOT NULL,
   id_unite VARCHAR(50)  NOT NULL,
   id_commande VARCHAR(50)  NOT NULL,
   id_article VARCHAR(150)  NOT NULL,
   PRIMARY KEY(id_commandeFille),
   FOREIGN KEY(id_unite) REFERENCES unite(id_unite),
   FOREIGN KEY(id_commande) REFERENCES commande(id_commande),
   FOREIGN KEY(id_article) REFERENCES article(id_article)
);

CREATE TABLE mvt_stock(
   id_mvt_stock VARCHAR(50) ,
   origine VARCHAR(50)  NOT NULL,
   date_ TIMESTAMP NOT NULL,
   description TEXT,
   etat INTEGER NOT NULL,
   id_magasin VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_mvt_stock),
   FOREIGN KEY(id_magasin) REFERENCES magasin(id_magasin)
);

CREATE TABLE proformaFournisseurFille(
   id_proformaFornisseurFille VARCHAR(50) ,
   prix_achat NUMERIC(15,2)   NOT NULL,
   id_proformaFournisseur VARCHAR(50)  NOT NULL,
   id_article VARCHAR(150)  NOT NULL,
   PRIMARY KEY(id_proformaFornisseurFille),
   FOREIGN KEY(id_proformaFournisseur) REFERENCES proformaFournisseur(id_proformaFournisseur),
   FOREIGN KEY(id_article) REFERENCES article(id_article)
);

CREATE TABLE bonCommande(
   id_bonCommande VARCHAR(50) ,
   date_ TIMESTAMP NOT NULL,
   etat INTEGER NOT NULL,
   id_utilisateur VARCHAR(50)  NOT NULL,
   id_proformaFournisseur VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_bonCommande),
   FOREIGN KEY(id_utilisateur) REFERENCES utilisateur(id_utilisateur),
   FOREIGN KEY(id_proformaFournisseur) REFERENCES proformaFournisseur(id_proformaFournisseur)
);

CREATE TABLE bonReception(
   id_bonReception VARCHAR(50) ,
   date_ TIMESTAMP NOT NULL,
   id_bonCommande VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_bonReception),
   FOREIGN KEY(id_bonCommande) REFERENCES bonCommande(id_bonCommande)
);

CREATE TABLE bonCommandeFille(
   id_bonCommandeFille VARCHAR(50) ,
   quantite NUMERIC(15,2)   NOT NULL,
   id_bonCommande VARCHAR(50)  NOT NULL,
   id_article VARCHAR(150)  NOT NULL,
   PRIMARY KEY(id_bonCommandeFille),
   FOREIGN KEY(id_bonCommande) REFERENCES bonCommande(id_bonCommande),
   FOREIGN KEY(id_article) REFERENCES article(id_article)
);

CREATE TABLE bonReceptionFille(
   id_bonReceptionFille VARCHAR(50) ,
   quantite NUMERIC(15,2)   NOT NULL,
   id_bonReception VARCHAR(50)  NOT NULL,
   id_article VARCHAR(150)  NOT NULL,
   PRIMARY KEY(id_bonReceptionFille),
   FOREIGN KEY(id_bonReception) REFERENCES bonReception(id_bonReception),
   FOREIGN KEY(id_article) REFERENCES article(id_article)
);

CREATE TABLE bon_livraison(
   id_bonLivraison VARCHAR(50) ,
   date_ TIMESTAMP NOT NULL,
   id_bonCommande VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_bonLivraison),
   FOREIGN KEY(id_bonCommande) REFERENCES bonCommande(id_bonCommande)
);

CREATE TABLE bonLivraisonFille(
   id_bonLivraisonFille VARCHAR(50) ,
   quantite VARCHAR(50)  NOT NULL,
   id_bonLivraison VARCHAR(50)  NOT NULL,
   id_article VARCHAR(150)  NOT NULL,
   PRIMARY KEY(id_bonLivraisonFille),
   FOREIGN KEY(id_bonLivraison) REFERENCES bon_livraison(id_bonLivraison),
   FOREIGN KEY(id_article) REFERENCES article(id_article)
);

CREATE TABLE mvt_stock_fille(
   id_mvt_stock_fille VARCHAR(50) ,
   entree NUMERIC(15,2)   NOT NULL,
   sortie NUMERIC(15,2)   NOT NULL,
   date_ TIMESTAMP NOT NULL,
   date_expiration TIMESTAMP,
   prix NUMERIC(15,2)   NOT NULL,
   id_article VARCHAR(150)  NOT NULL,
   id_mvt_stock VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_mvt_stock_fille),
   FOREIGN KEY(id_article) REFERENCES article(id_article),
   FOREIGN KEY(id_mvt_stock) REFERENCES mvt_stock(id_mvt_stock)
);
