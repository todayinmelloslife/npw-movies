<?php

  require_once("../19_moviestar/globals.php");
  require_once("../19_moviestar/db.php");
  require_once("../19_moviestar/models/Movie.php");
  require_once("../19_moviestar/models/Message.php");
  require_once(__DIR__ . '/../19_moviestar/models/dao/UserDAO.php');
  require_once(__DIR__ . '/../19_moviestar/models/dao/MovieDAO.php');

  $message = new Message($BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);

  // Resgata o tipo do formulário
  $type = filter_input(INPUT_POST, "type");

  // Resgata dados do usuário
  $userData = $userDao->verifyToken();

  // Função para converter qualquer link do YouTube para embed
  function getYouTubeEmbedUrl($url) {
    // Padrões para capturar o ID do vídeo
    $patterns = [
        '/youtu.be\\/([\w-]+)/', // youtu.be/ID
        '/youtube.com\\/watch\\?v=([\w-]+)/', // youtube.com/watch?v=ID
        '/youtube.com\\/embed\\/([\w-]+)/', // youtube.com/embed/ID
        '/youtube.com\\/shorts\\/([\w-]+)/', // youtube.com/shorts/ID
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
    }
    // Se já for embed, retorna como está
    if (strpos($url, 'youtube.com/embed/') !== false) {
        return $url;
    }
    // Se não for YouTube, retorna o original
    return $url;
}

  if($type === "create") {

    // Receber os dados dos inputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    // Converter o link do trailer para embed
    $trailer = getYouTubeEmbedUrl($trailer);
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");

    $movie = new Movie();

    // Validação mínima de dados
    if(!empty($title) && !empty($description) && !empty($category)) {

      $movie->title = $title;
      $movie->description = $description;
      $movie->trailer = $trailer;
      $movie->category = $category;
      $movie->length = $length;
      $movie->users_id = $userData->id;

      // Upload de imagem do filme
      if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

        $image = $_FILES["image"];
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        // Checando tipo da imagem
        if(in_array($image["type"], $imageTypes)) {

          // Checa se imagem é jpg
          if(in_array($image["type"], $jpgArray)) {
            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
          } else {
            $imageFile = imagecreatefrompng($image["tmp_name"]);
          }

          // Gerando o nome da imagem
          $imageName = $movie->imageGenerateName();

          // Corrigido: salvar na pasta correta
          imagejpeg($imageFile, __DIR__ . '/../19_moviestar/img/movies/' . $imageName, 100);

          $movie->image = $imageName;

        } else {

          $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

        }

      }

      $movieDao->create($movie);

    } else {

      $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");

    }

  } else if($type === "delete") {

    // Recebe os dados do form
    $id = filter_input(INPUT_POST, "id");

    $movie = $movieDao->findById($id);

    if($movie) {

      // Verificar se o filme é do usuário
      if($movie->users_id === $userData->id) {

        $movieDao->destroy($movie->id);

      } else {

        $message->setMessage("Informações inválidas!", "error", "index.php");

      }

    } else {

      $message->setMessage("Informações inválidas!", "error", "index.php");

    }

  } else if($type === "update") { 

    // Receber os dados dos inputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    // Converter o link do trailer para embed
    $trailer = getYouTubeEmbedUrl($trailer);
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    $id = filter_input(INPUT_POST, "id");

    $movieData = $movieDao->findById($id);

    // Verifica se encontrou o filme
    if($movieData) {

      // Verificar se o filme é do usuário
      if($movieData->users_id === $userData->id) {

        // Validação mínima de dados
        if(!empty($title) && !empty($description) && !empty($category)) {

          // Edição do filme
          $movieData->title = $title;
          $movieData->description = $description;
          $movieData->trailer = $trailer;
          $movieData->category = $category;
          $movieData->length = $length;

          // Upload de imagem do filme
          if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

            $image = $_FILES["image"];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray = ["image/jpeg", "image/jpg"];

            // Checando tipo da imagem
            if(in_array($image["type"], $imageTypes)) {

              // Checa se imagem é jpg
              if(in_array($image["type"], $jpgArray)) {
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);
              } else {
                $imageFile = imagecreatefrompng($image["tmp_name"]);
              }

              // Gerando o nome da imagem
              $movie = new Movie();

              $imageName = $movie->imageGenerateName();

              // Corrigido: salvar na pasta correta
              imagejpeg($imageFile, __DIR__ . '/../19_moviestar/img/movies/' . $imageName, 100);

              $movieData->image = $imageName;

            } else {

              $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

            }

          }

          $movieDao->update($movieData);

        } else {

          $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");

        }

      } else {

        $message->setMessage("Informações inválidas!", "error", "index.php");

      }

    } else {

      $message->setMessage("Informações inválidas!", "error", "index.php");

    }
  
  } else {

    $message->setMessage("Informações inválidas!", "error", "index.php");

  }