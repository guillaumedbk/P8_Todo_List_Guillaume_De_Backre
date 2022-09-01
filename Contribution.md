## Structure
Branche ‘master’: c’est cette branche qui aura pour but d’être déployée et qui comprend donc l’ensemble du code à jour et fonctionnel.

Les nouvelles fonctionnalités à développer ne sont donc pas directement commit sur la branche master. 

Il est nécessaire de créer une nouvelle branche pour chaque fonctionnalité et de faire une pull request une fois terminé. 

Le but étant d’avoir une vérification sur ce qui a été fait, de veiller à la qualité du code et à l’implémentation des bonnes pratiques.


## Règle de nommage
Nom de branche: <nom de la fonctionnalité - entité en lien - numéro du ticket lié >
Exemple: “Authentication(User)/#12”

Message de commit: <type de contribution - entité en lien - numéro du ticket lié - message >
Exemple: “feature(Task)/#14 creation task method”

Les types de contribution: 
‘feature’  : développement d’une nouvelle fonctionnalité
‘fix’: résolution de bug/correction apporté au code sur base de remarque ou autre
‘test’: dans le cadre de l’implémentation de tests automatisés  

Langue: Anglais
## Processus de qualité à respecter
Pour la qualité de code, l’outil d’analyse utilisé est symfony Insight. Chaque itération nécessite de vérifier le rapport d’analyse fourni par SI. Si des erreurs sont remontées ou que le code n’est pas validé, il est primordial d’y apporter les modifications nécessaires. Un code n’ayant pas été validé au préalable par la plate-forme ne pourra être merge sur la branche principale. Aucune erreur/suggestion ne peut être présente.
