<?php get_header(); ?>
<?php $bandeau = get_field('bandeau'); ?>  

<!-- Bandeau  -->
<?php if ($bandeau):?>
  <div>
    <div class="main_banner" style="background-image: url(' <? echo $bandeau['image']['url']?>');" title="<? echo $bandeau['image']['alt'];?>" >
      <? echo $bandeau['texte'] ?>
    </div>
    
  </div>

<?endif; ?>

<!-- Evenements  -->
<h4 class='dark_filet'><? echo the_field('titre_planning'); ?></h4> 

<?php $args = array('post_type' => 'evenement',
                    'taxonomy' => 'jour'
);

$termsJour = get_terms( array(
'taxonomy' => 'jour',
'orderby' => 'ID',
'order' => 'ASC') );?>
<div class="filter_container">

<div>
<div class="filter_title"><img class='date-svg'src='<?echo (IMAGES_URL . '/Calendar.svg') ?>'>DATE</div>
<select class="date_filter filters" data-filter='date'>
<option value="">All</option>
<?for ($i = 0; $i < sizeof($termsJour); $i++) {?>
   <option value="<?echo($termsJour[$i]->slug);?>">
     <? echo($termsJour[$i]->name);?>
</option>

<?}?>
</select>
</div>

<?$termsLieu = get_terms( array('taxonomy' => 'lieu') );?>

<div>
<div class="filter_title" ><img class='location-svg'src='<?echo (IMAGES_URL . '/Location.svg') ?>'>LOCATION</div>
<select class="location_filter filters" data-filter='location'>
  <option value="">All</option>
<?for ($i = 0; $i < sizeof($termsLieu); $i++) {?>
  <option value="<?echo($termsLieu[$i]->slug);?>">
     <? echo($termsLieu[$i]->name);?>
</option>
<?}?>
</select>
</div>

<? $termsType = get_terms( array('taxonomy' => 'type_event') );?>

<div>
<div class="filter_title" ><img class='type-svg'src='<?echo (IMAGES_URL . '/Location.svg') ?>'>TYPE</div>
<select class="type_filter filters" data-filter='type'>
<option value="">All</option>
<?for ($i = 0; $i < sizeof($termsType); $i++) {?>
  <option value="<?echo($termsType[$i]->slug);?>" >
     <? echo($termsType[$i]->name);?>
</option>
<?}?>
</select>
</div>
</div>

<? $termsTag = get_terms( array('taxonomy' => 'tag_event') );?>


<!-- NOUVEAU SELECTEUR DE TAG SUR LA HOME PAGE --> 


<!-- FIN --> 

<div class="main_planning">
<?for ($i = 0; $i < sizeof($termsJour); $i++){?> <!--Iterate through days-->
  
<h5 class='day_title'><?echo($termsJour[$i]->name);?></h5> <!-- Display day as title -->

<div class="planning_container"> <!-- Query events from the day being currently iterated -->
<?$my_query = new WP_Query(array(
    'post_type' => 'evenement',
    'tax_query' => array(
        array(
            'taxonomy' => 'jour',
            'field' => 'slug',
            'terms' => $termsJour[$i]->slug 
        )
    )
  )
        );

if($my_query->have_posts()) : while ($my_query->have_posts() ) : $my_query->the_post(); // iterate through events and displays them

$image = get_field('image_evenement');
$id = get_the_ID();
$jourSlug = get_the_terms($id, 'jour');
$jour = $jourSlug[0]->slug;
$lieuSlug = get_the_terms($id, 'lieu');
$lieu = $lieuSlug[0]->slug;
$typeSlug = get_the_terms($id, 'type_event');
$type = $typeSlug[0]->slug;

?>

<div class="event_box" date="<?echo($jour);?>"location="<?echo($lieu);?>"type="<?echo($type);?>">
  <a class="event_link" href="<? echo the_permalink();?>">
    <div class="event_img" style="background-image:url('<?echo $image['url']?>')" title="<?echo $image['alt']?>"></div>
    <h4 class="event_title"><? echo the_title()?></h4>
    <p class="event_hours"><img class='hour-svg'src='<?echo (IMAGES_URL . '/Clock.svg')?>'><? echo the_field('heure_evenement'); ?></p>
    <p><img class='location-svg'src='<?echo (IMAGES_URL . '/Location.svg')?>'><?php the_field('lieu_evenement'); ?></p>
   </a>
</div>
<?php
   
    
    
	wp_reset_postdata(); 
endwhile;
endif;?>
</div>
<?}?>
</div >
<!-- Direct  -->

<h4 class='dark_filet' ><? echo the_field('titre_diffusion'); ?></h4> 
<div class="stream_wrapper">
<?php the_field('youtube'); ?>
</div>

<!-- Partenaires  -->
<h4 class='dark_filet' ><? echo the_field('titre_sponsors'); ?></h4> 
<div class="sponsor-slider">
  <div class="next"></div>
  <div class="previous"></div>


<?php $args = array('post_type' => 'partenaire');?>

<?$my_query = new WP_Query($args);
if($my_query->have_posts()) : $counter=0; $numerator=0; while ($my_query->have_posts() ) : $my_query->the_post();
  if ($counter % 3 == 0){
    echo ("<div class='slide' data-slide=".$numerator.">");
    
  }
  $image = get_field('image_partenaire');?>
  <a href="<?echo the_field('lien_partenaire');?>"><img src="<? echo $image['url']; ?>" alt="<? echo $image['alt']; ?>"></a>
  <?
  if ((($counter+1) % 3 == 0 )||(($my_query->current_post +1) == ($my_query->post_count))){
    echo ("</div>");
    $numerator++;
  }
  
  $counter++;
  wp_reset_postdata(); 
endwhile;
endif; ?>
</div>
<div class="sponsor-list">
<?$my_query = new WP_Query($args);
if($my_query->have_posts()) : while ($my_query->have_posts() ) : $my_query->the_post();
  
  $image = get_field('image_partenaire');?>
  <a href="<?echo the_field('lien_partenaire');?>"><img src="<? echo $image['url']; ?>" alt="<? echo $image['alt']; ?>"></a>
  
<?
endwhile;
endif; ?>
</div>

<?php get_footer(); ?>
