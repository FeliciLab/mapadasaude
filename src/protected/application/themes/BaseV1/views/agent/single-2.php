<?php
$action = preg_replace("#^(\w+/)#", "", $this->template);
$this->bodyProperties['ng-app'] = "entity.app";
$this->bodyProperties['ng-controller'] = "EntityController";

$this->addEntityToJs($entity);

if($this->isEditable()){
    $this->addEntityTypesToJs($entity);
    $this->addTaxonoyTermsToJs('area');
    $this->addTaxonoyTermsToJs('tag');
}

$this->includeMapAssets();
$this->includeAngularEntityAssets($entity);
$editEntity = $this->controller->action === 'create' || $this->controller->action === 'edit';

?>
<?php
    $can_edit = true;
    $app->applyHook('can-edit', ['can_edit' => &$can_edit, 'entity' => $entity]);
?>
<?php $this->applyTemplateHook('breadcrumb','begin'); ?>

<?php $this->part('singles/breadcrumb', ['entity' => $entity,'entity_panel' => 'agents','home_title' => 'entities: My Agents']); ?><!--.part/singles/breadcrumb.php -->

<?php $this->applyTemplateHook('breadcrumb','end'); ?>

<?php /*if($can_edit) */$this->part('editable-entity', array('entity'=>$entity, 'action'=>$action)); ?><!--.part/editable-entity.php -->

<article class="main-content agent">
    <?php $this->applyTemplateHook('main-content','begin'); ?>
    
    <?php $this->part("singles/agent-header",['entity' => $entity]) ?>

    <?php $this->applyTemplateHook('tabs','before'); ?>
    <ul class="abas clearfix clear">
        <?php $this->applyTemplateHook('tabs','begin'); ?>
        <li class="active"><a href="#sobre" rel='noopener noreferrer'><?php \MapasCulturais\i::_e("Sobre");?></a></li>
        <?php if(!($this->controller->action === 'create')):?>
        <li><a href="#permissao" rel='noopener noreferrer'><?php \MapasCulturais\i::_e("Responsáveis");?></a></li>
        <?php endif;?>
        <?php $this->applyTemplateHook('tabs','end'); ?>
    </ul>
    <?php $this->applyTemplateHook('tabs','after'); ?>

    <div class="tabs-content">
        <?php $this->applyTemplateHook('tabs-content','begin'); ?>
        <div id="sobre" class="aba-content">
            <?php $this->applyTemplateHook('tab-about','begin'); ?>
            
            <?php $this->part("singles/agent-form-{$entity->type}", ['entity' => $entity, 'editEntity' => $editEntity]); ?><!--.part/singles/agent-form.php -->
            <?php if ( $this->isEditable() || $entity->longDescription ): ?>
                <h3><?php \MapasCulturais\i::_e("Descrição");?></h3>
                <span class="descricao js-editable <?php echo ($entity->isPropertyRequired($entity,"longDescription") && $this->isEditable()? 'required': '');?>" data-edit="longDescription" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Descrição do Agente");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Insira uma descrição do agente");?>" ><?php echo $this->isEditable() ? $entity->longDescription : nl2br($entity->longDescription); ?></span>
            <?php endif; ?>

                <?php $this->part('video-gallery.php', array('entity'=>$entity)); ?><!--.part/video-gallery.php -->

                <?php $this->part('gallery.php', array('entity'=>$entity)); ?><!--.part/gallery.php -->

            <?php $this->applyTemplateHook('tab-about','end'); ?>
        </div>
        <!-- #sobre -->
        <?php $this->part('singles/permissions') ?><!--.part/singles/permissions.php -->

        <?php $this->applyTemplateHook('tabs-content','end'); ?>
    </div>
    <!-- .tabs-content -->
    <?php $this->applyTemplateHook('tabs-content','after');?>
    
    <?php $this->part('owner', array('entity' => $entity, 'owner' => $entity->owner)); ?><!--.part/owner.php -->

    <?php $this->applyTemplateHook('main-content','end'); ?>
</article>
<div class="sidebar-left sidebar agent">
    <?php $this->applyTemplateHook('sidebar-left','begin'); ?>
    
    <?php $this->part('related-seals.php', array('entity'=>$entity)); ?>
    
    <?php $this->part('widget-tags', array('entity'=>$entity)); ?>

    <?php $this->applyTemplateHook('sidebar-left','end'); ?>
</div>
<div class="sidebar agent sidebar-right">
    <?php $this->applyTemplateHook('sidebar-right','begin'); ?>

    <?php if($this->controller->action == 'create'): ?>
        <div class="widget">
            <p class="alert info"><?php \MapasCulturais\i::_e("Para adicionar arquivos para download ou links, primeiro é preciso salvar o agente.");?><span class="close"></span></p>
        </div>
    <?php endif; ?>

        <?php $this->part('related-admin-agents.php', array('entity'=>$entity)); ?>
    
        <?php $this->part('related-agents.php', array('entity'=>$entity)); ?>
    
        <?php $this->part('singles/list-entities.php', array('entities'=>$entity->spaces, 'title' => 'entities: Spaces of the agent')); ?>
    
        <?php $this->part('singles/list-relations.php', array('entities'=>$entity)); ?>
    
        <?php $this->part('singles/list-entities.php', array('entities'=>$entity->projects, 'title' => 'entities: Projects of the agent')); ?>
    
        <?php $this->part('singles/list-entities.php', array('entities'=>$entity->children, 'title' => 'entities: Agent children')); ?>
    
        <?php $this->part('downloads.php', array('entity'=>$entity)); ?>
    
        <?php $this->part('link-list.php', array('entity'=>$entity)); ?>
    
        <?php $this->part('history.php', array('entity' => $entity)); ?>

        <?php $this->applyTemplateHook('sidebar-right','end'); ?>
</div>
