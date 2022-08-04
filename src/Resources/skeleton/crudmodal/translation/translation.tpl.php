<?= $entity_class_name ?>.index.pagetitle: 'CRUD'
<?= $entity_class_name ?>.index.subtitle: '> <?= $entity_class_name ?>'
<?= $entity_class_name ?>.index.pictotitle: ''
<?= $entity_class_name ?>.index.card-title: 'Liste des <?= $entity_class_name ?>'
<?= $entity_class_name ?>.index.btn_ajouter: 'Ajouter'

<?= $entity_class_name ?>.show.pagetitle: 'CRUD'
<?= $entity_class_name ?>.show.subtitle: '> <?= $entity_class_name ?>'
<?= $entity_class_name ?>.show.card-title: 'Détail d''un <?= $entity_class_name ?>'
<?= $entity_class_name ?>.show.pictotitle: ''
<?= $entity_class_name ?>.show.btn_supprimer: 'Supprimer'
<?= $entity_class_name ?>.show.btn_retourliste: 'Retour à la liste'

<?= $entity_class_name ?>.new.modaltitle: 'Ajouter un <?= $entity_class_name ?>'
<?= $entity_class_name ?>.new.error: 'Une erreur est survenue lors de l''enregistrement'
<?= $entity_class_name ?>.new.success: 'Enregistrement effectué avec succès'

<?= $entity_class_name ?>.edit.modaltitle: 'Editer un <?= $entity_class_name ?>'
<?= $entity_class_name ?>.edit.error: 'Une erreur est survenue lors de l''édition'
<?= $entity_class_name ?>.edit.success: 'Modification effectuée avec succès'

<?= $entity_class_name ?>.delete.error: 'Une erreur est survenue lors de la suppression'
<?= $entity_class_name ?>.delete.success: 'Suppression effectuée avec succès'

<?= $entity_class_name ?>.btn_fermer: 'Fermer'
<?= $entity_class_name ?>.btn_valider: '<i class="bi-check"></i> Valider'
<?= $entity_class_name ?>.btn_mettreajour: '<i class="bi-check"></i> Mettre à jour'
<?= $entity_class_name ?>.btn_confirm: 'Confirmer'
<?= $entity_class_name ?>.modal.deletemessage: 'Êtes-vous sûr de vouloir supprimer cet <?= $entity_class_name ?> ?
<br><br><strong>/!\ Cette action est irréversible !!!</strong>'

<?php foreach ($entity_fields as $field): ?>
<?= $entity_class_name ?>.<?= $field['fieldName'] ?>: '<?= $field['fieldName'] ?>'
<?php endforeach; ?>
<?= $entity_class_name ?>.action: ''
