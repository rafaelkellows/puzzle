<?php

	/** Product **/
	class Usuario {
		//cnstrc
		public function __construct( $id, $cid, $sid, $modified, $cover, $title, $resume, $min_price, $max_price, $size, $color, $weight ){ 
			$this->id = $id;
			$this->cid = $cid;
			$this->sid = $sid;
			$this->modified = $modified;
			$this->cover = $cover;
			$this->title = $title;
			$this->resume = $resume;
			$this->max_price = $max_price;
			$this->min_price = $min_price;
			$this->size = $size;
			$this->color = $color;
			$this->weight = $weight;
		}

		public function prodItem(){
			echo '<dl id="'.$this->id.'" data-modified="'.(($this->modified) ? $this->modified : '' ).'" class="prod-item">';
			echo " 	<input class='prod-title' type='hidden' name='title' value='".$this->title."' />";
			echo " 	<input class='prod-resume' type='hidden' name='resume' value='".$this->resume."' />";
			echo " 	<input class='prod-cover' type='hidden' name='cover' value='".$this->cover."' />";
			echo '	<dt><strong>Cód. '.$this->id.'</strong><a class="prod-title" href="./produto.php?id_prod='.$this->id.'&cat='.$this->cid.'&sub='.$this->sid.'" title="'.$this->title.'">'.$this->title.'</a></dt>';
			echo '	<dd>';
			echo '		<span class="label"><i class="fa fa-check-circle" aria-hidden="true"></i>Item Adicionado</span>';
			echo '		<img src="'.$this->cover.'" title="'.$this->title.'" />';
			if($this->resume){ echo '<span class="resume">'.$this->resume.'</span>' ; }			
			echo '		<p class="lbl"><strong>R$'.$this->max_price.'</strong><em> unidade</em></p> ';
			
			// SIZE
			$array = explode(',', $this->size);
			echo "		<ul class='size'>";
			if ( count($array) > 1 ) { echo '<em>Disponíveis em</em>'; }else{ echo '<em>Disponível em:</em>'; };
			foreach($array as $size){
				echo "		<li class='". (($size=='') ? "unico" : null ) ."'>". (($size!='') ? $size : "único")  . '</li>';
			}
			echo "		</ul>";

			// COLOR
			if ($this->color->rowCount() > 0) {
				echo "		<ul class='color'>";
				echo "			<em>e na opção:</em>";
				while ( $row_colors = $this->color->fetch(PDO::FETCH_ASSOC) ) {
					if($row_colors['color']!=''){
						echo "	<li style='background-color:#".$row_colors['color']."; ". (( $row_colors['color'] == 'ffffff') ? 'border:1px solid #f4f4f4;' : '' )."' title='".$row_colors['label']."'>".$row_colors['color']."</li>";
					}
				}
				echo "		</ul>";
			}

			// BUTTONS
			echo '		<a class="btn-color-E btn-checkout" href="checkout.php?id_row='.$this->id.'&min='.$this->min_price.'&max='.$this->max_price.'&weight='.$this->weight.'" title="Adicionar ao Carrinho"><i class="fa fa-shopping-cart"></i> Adicionar ao Carrinho</a>';
			echo '		<a class="btn-color-B" href="orcamento.php?id_prod='.$this->id.'&cat='.$this->cid.'&sub='.$this->sid.'&capa='.$this->cover.'" title="Solicitar Orçamento"><i class="fa fa-edit"></i> Solicitar Orçamento</a>';
			echo '		<a class="btn-short" href="./produto.php?id_prod='.$this->id.'&cat='.$this->cid.'&sub='.$this->sid.'" title="Ver mais detalhes"><i class="fa fa-plus"></i> ver mais detalhes</a>';
			echo "	</dd>";
			echo "</dl>";
		}
		public function itemDescription($connection,$prod_row){
        	/* Título */
    		echo "<h2 class='bgTitle'>" .$this->title. "</h2>";
    		echo "<a class='btn-back' href='javascript:window.history.back();'>voltar</a>";

			echo "<div>";
			//Gallery
			$oSlctImages = $connection->SQLselector("*","galeria",'pid = '.$this->id,'id ASC');
			if( $oSlctImages->rowCount() > 1){
				echo "<p style='width:50%'><em>Deslize para ver as outras imagens</em></p>";
			}
			echo "	<ul id='owl-product'>";
					while ( $row = $oSlctImages->fetch(PDO::FETCH_ASSOC) ) {
						echo "<li><img src='".$row["src"]."' alt='' /></li>";
					}
			echo "	</ul>";
			
			//Product Shopping Information
			echo '	<div id="'.$this->id.'" class="shopping_info prod-item">';
			echo "		<input class='prod-title' type='hidden' name='title' title='".$this->title."' value='".$this->title."' />";
			echo "		<input class='prod-resume' type='hidden' name='resume' value='".$this->resume."' />";
			echo "		<input class='prod-cover' type='hidden' name='cover' value='".$this->cover."' />";
			echo "		<span><i class='fa fa-check-circle' aria-hidden='true'></i>Item Adicionado</span>";
			echo '		<p class="lbl"><strong>R$'.$this->max_price.'</strong><em> unidade</em></p> ';

			// SIZE
			$array = explode(',', $this->size);
			echo "		<ul class='size'>";
			
			if ( count($array) > 1 ) { echo '<em>Disponíveis em</em>'; }else{ echo '<em>Disponível em:</em>'; };
			foreach($array as $size){
				echo "		<li class='". (($size=='') ? "unico" : null ) ."'>". (($size!='') ? $size : "único")  . '</li>';
			}
			echo "		</ul>";
			// COLOR
			//$colorFecth = '';
           	if ($this->color->rowCount() > 0) {
    			echo "<ul class='color'>";
				echo '<em>e na opção:</em>';
				//$colorFecth = $this->color->fetch(PDO::FETCH_ASSOC);
				while ( $row_colors = $this->color->fetch(PDO::FETCH_ASSOC) ) {
					echo "<li style='background-color:#".$row_colors['color']."; ". (( $row_colors['color'] == 'ffffff') ? 'border:1px solid #f4f4f4;' : '' )."' title='".$row_colors['label']."'>".$row_colors['color']."</li>";
				}
    			echo "</ul>";
            }
			// BUTTONS
			echo '  <a class="btn-default btn-color-E btn-checkout" href="checkout.php?id_rows='.$this->id.'&min='.$this->min_price.'&max='.$this->max_price.'&weight='.$this->weight.'" alt="Adicionar ao Carrinho"><i class="fa fa-shopping-cart"></i> Adicionar ao Carrinho</a>';
			echo '	<a class="btn-default btn-color-B" href="orcamento.php?id_prod='.$this->id.'&cat='.$this->cid.'&sub='.$this->sid.'&capa='.$this->cover.'" alt="Solicitar Orçamento"><i class="fa fa-edit"></i> Solicitar Orçamento</a>';
			
			echo "	</div>";
			echo "</div>";

        	/* Product Description */
        	echo "<section class='product_description'>";
    		
    		if( !empty($prod_row['description'])){
    			echo "<p>";
    			echo "	<strong>Descrição do Produto</strong><br>" .$prod_row['description'];
    			echo "</p>";
    		}
    		if (!empty( $this->size)){
    			echo "<p>";
				if ( count( $this->size ) > 1 ) { echo '<strong>Disponíveis em</strong><br>'; }else{ echo '<strong>Disponível em</strong><br>'; };
				echo $this->size;
    			echo "</p>";
    		}

            if (!empty($this->color)) {
				$this->color = $connection->SQLselector("*","colors","pid='".$this->id."'",'id ASC');
    			echo "<p>";
				if ($this->color->rowCount() > 1) { echo '<strong>Opções</strong><br>'; }else{ echo '<strong>Opção</strong><br>'; };
				while ( $row_colors = $this->color->fetch(PDO::FETCH_ASSOC) ) {
					echo "<span class='color' style='background-color:#".$row_colors['color']."; ". (( $row_colors['color'] == 'ffffff') ? 'border:1px solid #f4f4f4;' : '' )."' title='".$row_colors['label']."'><em>".$row_colors['label']."</em></span>";
				}
    			echo "</p>";
            }
    		if( $prod_row['length'] > 0 && $prod_row['width'] > 0 && $prod_row['depth'] > 0 && $prod_row['radius'] == 0 ){
    			echo "<p>";
    			echo "	<strong>Dimensões (C x L x P)</strong><br>" .$rows['length']. " x ".$rows['width']. " x ".$rows['depth']. " cm " ;
    			echo "</p>";
    		}else{
    			if( isset($prod_row['radius']) && !empty($prod_row['radius'])){
        			echo "<p>";
        			echo "	<strong>Raio Maior e Altura (R x A)</strong><br>" .$prod_row['radius']. " x ".$prod_row['height']. " cm " ;
        			echo "</p>";
        		}
    		}
    		if(!empty($prod_row['weight'])){
    			echo "<p>";
    			echo "	<strong>Peso</strong><br>".$prod_row['weight']. " kg" ;
    			echo "</p>";
    		}

        	echo "</section>";
			echo "<div class='socials-links'>";
			echo "	<span class=text>Compartilhe este produto: </span>";
			echo "	<span class='st_facebook_large' displayText='Facebook'></span>";
			echo "	<span class='st_twitter_large' displayText='Tweet'></span>";
			echo "	<span class='st_linkedin_large' displayText='LinkedIn'></span>";
			echo "	<span class='st_googleplus_large' displayText='Google +'></span>";
			echo "	<span class='st_email_large' displayText='Email'></span>";
			echo "</div>";
		}
	}

	/** set Itens stored on Cookie 'itens' **/
	class CookieItens{
		public function __construct(){
			$cookie_name = "itens";
			
			if(isset($_COOKIE[$cookie_name])) {
				$arrItensCookie = json_decode($_COOKIE[$cookie_name], true);
				$arr_length = count($arrItensCookie); 
			}else{
				$arr_length = 0; 
			}
			$slcComplement = '';

			for($i=0; $i<$arr_length; $i++){ 
				$slcComplement.=' AND id!='.$arrItensCookie[$i][4];
			}
			$this->total_itens = $arr_length;
			$this->sql_complement = $slcComplement;
		}
		public function getItens($i){
			switch ($i) {
			    case 'total':
					return $this->total_itens;
			        break;
			    case 'complement':
					return $this->sql_complement;
			        break;
			}
		}
	}
	$c_itens = new CookieItens();

?>