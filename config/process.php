<?php

  session_start();

  include_once("connection.php");
  include_once("url.php");

  $data = $_POST;

  // MODIFICAÇÕES NO BANCO
  if(!empty($data)) {

    // Criar contato
    if($data["type"] === "create") {
      $CodCliente = $data["CodCliente"];
      $Nome = $data["Nome"];
      $Endereco = $data["Endereco"];
      $Celular = $data["Celular"];     
      $Telefone = $data["Telefone"];
      $CPF = $data["CPF"];
      $observations = $data["observations"];

      $query = "INSERT INTO cliente1 (CodCliente, Nome, Endereco, Celular, Telefone, CPF) VALUES (:CodCliente, :Nome, :Endereco, :Celular, :Telefone, :CPF, :observations)";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":Nome", $Nome);
      $stmt->bindParam(":Endereco", $Endereco);
      $stmt->bindParam(":Celular", $Celular);
      $stmt->bindParam(":Telefone", $Telefone);
      $stmt->bindParam(":CPF", $CPF);
      $stmt->bindParam(":observations", $observations);

      try {

        $stmt->execute();
        $_SESSION["msg"] = "Contato criado com sucesso!";
    
      } catch(PDOException $e) {
        // erro na conexão
        $error = $e->getMessage();
        echo "Erro: $error";
      }

    } else if($data["type"] === "edit") {

      $CodCliente = $data["CodCliente"];
      $Nome = $data["Nome"];
      $Endereco = $data["Endereco"];
      $Celular = $data["Celular"];     
      $Telefone = $data["Telefone"];
      $CPF = $data["CPF"];
      $observations = $data["observations"];

      $query = "UPDATE cliente1 
                SET Nome = :Nome, Endereco, = :phone, observations = :observations 
                WHERE CodCliente = :CodCliente";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":Nome", $Nome);
      $stmt->bindParam(":Endereco", $Endereco);
      $stmt->bindParam(":Celular", $Celular);
      $stmt->bindParam(":Telefone", $Telefone);
      $stmt->bindParam(":CPF", $CPF);
      $stmt->bindParam(":observations", $observations);
      $stmt->bindParam(":CodCliente", $CodCliente);

      try {

        $stmt->execute();
        $_SESSION["msg"] = "Contato atualizado com sucesso!";
    
      } catch(PDOException $e) {
        // erro na conexão
        $error = $e->getMessage();
        echo "Erro: $error";
      }

    } else if($data["type"] === "delete") {

      $CodCliente = $data["CodCliente"];

      $query = "DELETE FROM cliente1 WHERE CodCliente = :CodCliente";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":CodCliente", $CodCliente);
      
      try {

        $stmt->execute();
        $_SESSION["msg"] = "Contato removido com sucesso!";
    
      } catch(PDOException $e) {
        // erro na conexão
        $error = $e->getMessage();
        echo "Erro: $error";
      }

    }

    // Redirect HOME
    header("Location:" . $BASE_URL . "../index.php");

  // SELEÇÃO DE DADOS
  } else {
    
    $CodCliente;

    if(!empty($_GET)) {
      $CodCliente = $_GET["CodCliente"];
    }

    // Retorna o dado de um contato
    if(!empty($CodCliente)) {

      $query = "SELECT * FROM contacts WHERE CodCliente = :CodCliente";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":CodCliente", $CodCliente);

      $stmt->execute();

      $contact = $stmt->fetch();

    } else {

      // Retorna todos os contatos
      $contacts = [];

      $query = "SELECT * FROM cliente1";

      $stmt = $conn->prepare($query);

      $stmt->execute();
      
      $contacts = $stmt->fetchAll();

    }

  }

  // FECHAR CONEXÃO
  $conn = null;