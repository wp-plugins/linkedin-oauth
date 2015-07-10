<?php

function checkVar($var) {
	$check = false;

	if (isset($var)) {
		if (!empty($var)) {
			$check = true;
		}
	}
	return $check;
}

// Genera un Ramdom String

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}

function userRandomString($length = 4) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}
// Funciones para imagenes

function subirImagen($user_id, $pictureUrl) {

	if ($pictureUrl != "") {

		$pictureUrl = str_replace("#", "/", $pictureUrl);

		//Cambia tamaño imagen

		$tipo = "image/jpeg";
		$extension = ".jpg";
		$image = @imagecreatefromjpeg($pictureUrl);
		if (!$image) {
			$tipo = "image/gif";
			$extension = ".gif";
			$image = @imagecreatefromgif($pictureUrl);
			if (!$image) {
				$tipo = "image/png";
				$extension = ".png";
				$image = @imagecreatefrompng($pictureUrl);
				if (!$image) {
					$tipo = "image/x-png";
					$extension = ".png";
					$image = @imagecreatefrompng($pictureUrl);
				}
			}
		}

		$carpetaImagen = ABSPATH . '/wp-content/uploads/avatars/' . $user_id;
		if (!is_dir($carpetaImagen)) {
			wp_mkdir_p($carpetaImagen);
		}
		$urlOrigen = $carpetaImagen . "/" . wp_hash($pictureUrl . time()) . "-bpfull" . $extension;

		if ($tipo == "image/jpeg") {
			imagejpeg($image, $urlOrigen);
		} else if ($tipo == "image/pjpeg") {
			imagejpeg($image, $urlOrigen);
		} else if ($tipo == "image/gif") {
			imagegif($image, $urlOrigen);
		} else if ($tipo == "image/png") {
			imagepng($image, $urlOrigen);
		} else if ($tipo == "image/x-png") {
			imagepng($image, $urlOrigen);
		}

		chmod($urlOrigen, 0777);

		recortarCuadradoConMedida($urlOrigen);
	}
}

function recortarCuadradoConMedida($urlOrigen, $anchoFinal = 50) {
	Redimensionar($urlOrigen, str_replace("-bpfull", "-bpthumb", $urlOrigen), $anchoFinal);
}

function Redimensionar($urlOrigen, $destino, $ancho) {

	//Cambia tamano imagen
	$image = @imagecreatefromjpeg($urlOrigen);
	if ($image) {
		$tipo = "image/jpeg";
	} else {
		$image = @imagecreatefromgif($urlOrigen);
		if ($image) {
			$tipo = "image/gif";
		} else {
			$image = @imagecreatefrompng($urlOrigen);
			if ($image) {
				$tipo = "image/png";
			} else {
				$image = @imagecreatefromwbmp($urlOrigen);
				if ($image) {
					$tipo = "image/x-ms-bmp";
				}

			}
		}
	}

	if ($image === false) {
		die('No se puedo abrir la imagen');
	}

	// Get original width and height
	$width = imagesx($image);
	$height = imagesy($image);
	// New width and height
	$new_width = $ancho;
	$new_height = ($height * $ancho) / $width;

	// Resample
	$image_resized = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	$ruta_peque = $destino;

	if ($tipo == "image/jpeg") {
		imagejpeg($image_resized, $ruta_peque);
	} else if ($tipo == "image/pjpeg") {
		imagejpeg($image_resized, $ruta_peque);
	} else if ($tipo == "image/gif") {
		imagegif($image_resized, $ruta_peque);
	} else if ($tipo == "image/png") {
		imagepng($image_resized, $ruta_peque);
	} else if ($tipo == "image/x-png") {
		imagepng($image_resized, $ruta_peque);
	} else if ($tipo == "image/x-ms-bmp") {
		imagewbmp($image_resized, $ruta_peque);
	}

	chmod($ruta_peque, 0777);

}

function ImageCreateFromBMP($filename) {
	//Ouverture du fichier en mode binaire
	if (!$f1 = fopen($filename, "rb")) {
		return FALSE;
	}

	//1 : Chargement des ent?tes FICHIER
	$FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
	if ($FILE['file_type'] != 19778) {
		return FALSE;
	}

	//2 : Chargement des ent?tes BMP
	$BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' .
		'/Vcompression/Vsize_bitmap/Vhoriz_resolution' .
		'/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));
	$BMP['colors'] = pow(2, $BMP['bits_per_pixel']);
	if ($BMP['size_bitmap'] == 0) {
		$BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
	}

	$BMP['bytes_per_pixel'] = $BMP['bits_per_pixel'] / 8;
	$BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
	$BMP['decal'] = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
	$BMP['decal'] -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
	$BMP['decal'] = 4 - (4 * $BMP['decal']);
	if ($BMP['decal'] == 4) {
		$BMP['decal'] = 0;
	}

	//3 : Chargement des couleurs de la palette
	$PALETTE = array();
	if ($BMP['colors'] < 16777216) {
		$PALETTE = unpack('V' . $BMP['colors'], fread($f1, $BMP['colors'] * 4));
	}

	//4 : Cr?ation de l'image
	$IMG = fread($f1, $BMP['size_bitmap']);
	$VIDE = chr(0);

	$res = imagecreatetruecolor($BMP['width'], $BMP['height']);
	$P = 0;
	$Y = $BMP['height'] - 1;
	while ($Y >= 0) {
		$X = 0;
		while ($X < $BMP['width']) {
			if ($BMP['bits_per_pixel'] == 24) {
				$COLOR = unpack("V", substr($IMG, $P, 3) . $VIDE);
			} elseif ($BMP['bits_per_pixel'] == 16) {
				$COLOR = unpack("n", substr($IMG, $P, 2));
				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
			} elseif ($BMP['bits_per_pixel'] == 8) {
				$COLOR = unpack("n", $VIDE . substr($IMG, $P, 1));
				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
			} elseif ($BMP['bits_per_pixel'] == 4) {
				$COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
				if (($P * 2) % 2 == 0) {
					$COLOR[1] = ($COLOR[1] >> 4);
				} else {
					$COLOR[1] = ($COLOR[1] & 0x0F);
				}

				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
			} elseif ($BMP['bits_per_pixel'] == 1) {
				$COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
				if (($P * 8) % 8 == 0) {
					$COLOR[1] = $COLOR[1] >> 7;
				} elseif (($P * 8) % 8 == 1) {
					$COLOR[1] = ($COLOR[1] & 0x40) >> 6;
				} elseif (($P * 8) % 8 == 2) {
					$COLOR[1] = ($COLOR[1] & 0x20) >> 5;
				} elseif (($P * 8) % 8 == 3) {
					$COLOR[1] = ($COLOR[1] & 0x10) >> 4;
				} elseif (($P * 8) % 8 == 4) {
					$COLOR[1] = ($COLOR[1] & 0x8) >> 3;
				} elseif (($P * 8) % 8 == 5) {
					$COLOR[1] = ($COLOR[1] & 0x4) >> 2;
				} elseif (($P * 8) % 8 == 6) {
					$COLOR[1] = ($COLOR[1] & 0x2) >> 1;
				} elseif (($P * 8) % 8 == 7) {
					$COLOR[1] = ($COLOR[1] & 0x1);
				}

				$COLOR[1] = $PALETTE[$COLOR[1] + 1];
			} else {
				return FALSE;
			}

			imagesetpixel($res, $X, $Y, $COLOR[1]);
			$X++;
			$P += $BMP['bytes_per_pixel'];
		}
		$Y--;
		$P += $BMP['decal'];
	}

	//Fermeture du fichier
	fclose($f1);

	return $res;
}
function clean_scriptslkd($url) {
	$urlclean = preg_replace('/((\%3C)|(\&lt;)|<)(script\b)[^>]*((\%3E)|(\&gt;)|>)(.*?)((\%3C)|(\&lt;)|<)(\/script)((\%3E)|(\&gt;)|>)|((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/is', "", $url);
	return $urlclean;
}

add_action('init', 'session_initlkd');

function session_initlkd() {
	if (isset($_GET['noheader'])) {
		require_once ABSPATH . 'wp-admin/admin-header.php';
	}
	if (isset($_REQUEST['state'])) {

		$state = clean_scriptslkd($_REQUEST['state']);

		if (!wp_verify_nonce($state, 'linkedinbutton')) {
			// Si el nonce no es valido lanzamos un error.
			wp_die(_e('You are making a not valid call'), 'Error', array('back_link' => true));
		} else {

			// state ok!
			$sessionstate = $state;
			$code = clean_scriptslkd($_GET['code']);
			$url_redirect = get_site_url();
			$opt_name_clientid = 'wp_lkd_clientid';
			$opt_name_clientsecret = 'wp_lkd_clientsecret';
			$opt_name_urlafter = 'wp_lkd_urlafter';
			$opt_name_register = "wp_lkd_register";
			$opt_val_clientid = get_option($opt_name_clientid);
			$opt_val_clientsecret = get_option($opt_name_clientsecret);
			$opt_val_urlafter = get_option($opt_name_urlafter);
			$opt_val_register = get_option($opt_name_register);
			$client_id = $opt_val_clientid;
			$client_secret = $opt_val_clientsecret;

			if (empty($opt_val_urlafter)) {
				$redirectadm = get_site_url() . '/wp-admin';
			} else {
				$redirectadm = $opt_val_urlafter;
			}

			$url = 'https://www.linkedin.com/uas/oauth2/accessToken';
			// wp remote request POST
			$args = array(
				'method' => 'POST',
				'httpversion' => '1.1',
				'blocking' => true,
				'body' => array(
					'grant_type' => 'authorization_code',
					'code' => $code,
					'redirect_uri' => $url_redirect,
					'client_id' => $client_id,
					'client_secret' => $client_secret,
				),
			);
			add_filter('https_ssl_verify', '__return_false');
			$data = wp_remote_post($url, $args);
			if (is_wp_error($data)) {
				$error_message = $data->get_error_message();
				echo "<script>alert('" . $error_message . "');</script>";
			}
			$data = $data['body'];
			$data = json_decode($data);
			$access_token = $data->access_token;
		}

		if (isset($access_token)) {
			$url = 'https://api.linkedin.com/v1/people/~?oauth2_access_token=' . $access_token . '&format=json';

			add_filter('https_ssl_verify', '__return_false');
			$api_url = "https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address,headline,industry,summary,positions,picture-url,skills,languages,educations,recommendations-received)?oauth2_access_token=$access_token&format=json";

			$response = wp_remote_get($api_url);
			$json = json_decode($response['body']);
			$email = $json->emailAddress;
			$name = $json->firstName;
			$familyname = $json->lastName;
			$usern = $name . "_" . userRandomString();
			$picture = $json->pictureUrl;

			if (email_exists($email)) {
				$user_id = email_exists($email);
				wp_set_auth_cookie($user_id);
				update_user_meta($user_id, "linkedin_access_token", $access_token);
				wp_redirect($redirectadm);
				exit();
			} else {
				if (!$opt_val_register) {
					wp_die(_e('Your Linkedin account doesn\'t match any user on this page'), 'Error', array('back_link' => true));
					exit;
				} else {
					//Genera un usuario con los datos de Linkedin
					$create = wp_create_user($usern, generateRandomString(), $email);
					if (is_wp_error($create)) {
						wp_die($create);
					}
					$user_id = email_exists($email);
					wp_set_auth_cookie($user_id);
					update_user_meta($user_id, "linkedin_access_token", $token);
					if ($picture != '') {subirImagen($user_id, $picture);}
					wp_redirect(get_site_url() . '/wp-admin');
					exit();
				}
			}
		} else {
			wp_die(_e('Error: No access token from Linkedin'));
		}
	} //endif
} //end session_init

function linkedinoauth_create_widget() {
	include_once plugin_dir_path(__FILE__) . 'widget.php';
	register_widget('linkedinoauth_widget');
}
add_action('widgets_init', 'linkedinoauth_create_widget');