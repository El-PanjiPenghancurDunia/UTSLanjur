  <?php
  $hlm = "Home";
  if(uri_string()!=""){
    $hlm = ucwords(uri_string());
  }
  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>- Warung - <?php echo $hlm ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?= base_url()?>NiceAdmin/assets/img/broccoli.png" rel="icon">
    <link href="<?= base_url()?>NiceAdmin/assets/img/vegetables.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= base_url()?>NiceAdmin/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url()?>NiceAdmin/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url()?>NiceAdmin/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?= base_url()?>NiceAdmin/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="<?= base_url()?>NiceAdmin/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?= base_url()?>NiceAdmin/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?= base_url()?>NiceAdmin/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?= base_url()?>NiceAdmin/assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
    * Template Name: NiceAdmin
    * Updated: Mar 09 2023 with Bootstrap v5.2.3
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
  </head>

  <body>
  <?= $this->include('components/header') ?>

  <?= $this->include('components/sidebar') ?>

    <main id="main" class="main">

      <div class="pagetitle">
        <h1><?= $hlm ?></h1>
        <nav>
        <ol class="breadcrumb">
    <li class="breadcrumb-item">Home</li>
    <?php
    if($hlm!="Home"){
      ?>
      <li class="breadcrumb-item"><?php echo $hlm?></li> 
      <?php
    }
    ?> 
  </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section">
        <div class="row">
          <div class="col-lg-12">

            <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?php echo $hlm?></h5>
              <?= $this->renderSection('content') ?>
              </div>  
            </div>

          </div>
        </div>
      </section>

    </main><!-- End #main -->

    <!-- ======================================================= -->
    <!--            KODE CHATBOT AI DIMULAI DI SINI              -->
    <!-- ======================================================= -->
    <style>
        .chatbot-toggler {
            position: fixed;
            bottom: 30px;
            right: 35px;
            outline: none;
            border: none;
            height: 50px;
            width: 50px;
            display: flex;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #0d6efd; /* Warna biru primer Bootstrap */
            transition: all 0.2s ease;
            z-index: 999;
        }
        .chatbot-toggler span {
            color: #fff;
            font-size: 1.8rem;
        }
        .chatbot {
            position: fixed;
            right: 35px;
            bottom: 90px;
            width: 420px;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
            transform: scale(0.5);
            transform-origin: bottom right;
            box-shadow: 0 0 128px 0 rgba(0,0,0,0.1), 0 32px 64px -48px rgba(0,0,0,0.5);
            transition: all 0.1s ease;
            z-index: 998;
        }
        .show-chatbot .chatbot {
            opacity: 1;
            pointer-events: auto;
            transform: scale(1);
        }
        .chatbot header {
            padding: 16px 0;
            position: relative;
            text-align: center;
            color: #fff;
            background: #0d6efd;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .chatbot header h5 {
            font-size: 1.2rem;
        }
        .chatbot .chatbox {
            overflow-y: auto;
            height: 400px;
            padding: 30px 20px 100px;
            background-color: #f7f7f7;
        }
        .chatbox .chat {
            display: flex;
            list-style: none;
        }
        .chatbox .chat p {
            white-space: pre-wrap;
            padding: 12px 16px;
            border-radius: 10px 10px 0 10px;
            max-width: 75%;
            color: #fff;
            font-size: 0.95rem;
            background: #0d6efd;
        }
        .chatbox .incoming span {
            width: 32px;
            height: 32px;
            color: #fff;
            cursor: default;
            text-align: center;
            line-height: 32px;
            align-self: flex-end;
            background: #0d6efd;
            border-radius: 4px;
            margin: 0 10px 7px 0;
        }
        .chatbox .outgoing {
            margin: 20px 0;
            justify-content: flex-end;
        }
        .chatbox .outgoing p {
            border-radius: 10px 10px 10px 0;
            background: #e9e9e9;
            color: #333;
        }
        .chatbot .chat-input {
            display: flex;
            gap: 5px;
            position: absolute;
            bottom: 0;
            width: 100%;
            background: #fff;
            padding: 10px 20px;
            border-top: 1px solid #ddd;
        }
        .chat-input textarea {
            height: 55px;
            width: 100%;
            border: none;
            outline: none;
            resize: none;
            max-height: 180px;
            padding: 15px;
            font-size: 0.95rem;
        }
        .chat-input button {
            align-self: flex-end;
            height: 55px;
            width: 55px;
            border: none;
            outline: none;
            cursor: pointer;
            font-size: 1.2rem;
        }
    </style>

    <!-- Tombol untuk membuka/menutup chatbot -->
    <button class="chatbot-toggler">
        <span class="bi bi-chat-dots"></span>
    </button>

    <!-- Kontainer Chatbot -->
    <div class="chatbot">
        <header>
            <h5>Asisten Virtual</h5>
        </header>
        <ul class="chatbox list-unstyled">
            <li class="chat incoming">
                <span class="bi bi-robot"></span>
                <p>Halo! 👋<br>Ada yang bisa saya bantu terkait produk kami?</p>
            </li>
        </ul>
        <div class="chat-input">
            <textarea placeholder="Ketik pertanyaan Anda..." required></textarea>
            <button class="btn btn-primary"><i class="bi bi-send"></i></button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chatbotToggler = document.querySelector(".chatbot-toggler");
            const chatInput = document.querySelector(".chat-input textarea");
            const sendChatBtn = document.querySelector(".chat-input button");
            const chatbox = document.querySelector(".chatbox");

            let userMessage;
            // Ganti csrf_token() dengan nama token CSRF Anda jika berbeda
            const csrfTokenName = '<?= csrf_token() ?>';
            const csrfTokenValue = '<?= csrf_hash() ?>';

            const createChatLi = (message, className) => {
                const chatLi = document.createElement("li");
                chatLi.classList.add("chat", className);
                let chatContent = className === "outgoing" ? `<p>${message}</p>` : `<span class="bi bi-robot"></span><p>${message}</p>`;
                chatLi.innerHTML = chatContent;
                return chatLi;
            }

            const generateResponse = (incomingChatLi) => {
                const API_URL = "<?= base_url('ask-ai') ?>";
                const messageElement = incomingChatLi.querySelector("p");

                const requestOptions = {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: `question=${encodeURIComponent(userMessage)}&${csrfTokenName}=${csrfTokenValue}`
                };

                fetch(API_URL, requestOptions).then(res => res.json()).then(data => {
                    if (data.answer) {
                        messageElement.textContent = data.answer;
                    } else {
                        messageElement.textContent = "Maaf, terjadi kesalahan. Silakan coba lagi.";
                    }
                }).catch((error) => {
                    messageElement.textContent = "Oops! Ada yang salah. Tidak bisa mendapatkan jawaban.";
                }).finally(() => chatbox.scrollTo(0, chatbox.scrollHeight));
            }

            const handleChat = () => {
                userMessage = chatInput.value.trim();
                if(!userMessage) return;
                chatInput.value = "";

                chatbox.appendChild(createChatLi(userMessage, "outgoing"));
                chatbox.scrollTo(0, chatbox.scrollHeight);
                
                setTimeout(() => {
                    const incomingChatLi = createChatLi("Mengetik...", "incoming");
                    chatbox.appendChild(incomingChatLi);
                    chatbox.scrollTo(0, chatbox.scrollHeight);
                    generateResponse(incomingChatLi);
                }, 600);
            }

            sendChatBtn.addEventListener("click", handleChat);
            chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));
            chatInput.addEventListener("keydown", (e) => {
                if(e.key === "Enter" && !e.shiftKey) {
                    e.preventDefault();
                    handleChat();
                }
            });
        });
    </script>
    <!-- ======================================================= -->
    <!--             KODE CHATBOT AI SELESAI DI SINI             -->
    <!-- ======================================================= -->

    <?= $this->include('components/footer') ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Vendor JS Files -->
    <script src="<?php base_url()?>NiceAdmin/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="<?php base_url()?>NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php base_url()?>NiceAdmin/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="<?php base_url()?>NiceAdmin/assets/vendor/echarts/echarts.min.js"></script>
    <script src="<?php base_url()?>NiceAdmin/assets/vendor/quill/quill.min.js"></script>
    <script src="<?php base_url()?>NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="<?php base_url()?>NiceAdmin/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="<?php base_url()?>NiceAdmin/assets/vendor/php-email-form/validate.js"></script>
    <!-- Template Main JS File -->
    <script src="<?php base_url()?>NiceAdmin/assets/js/main.js"></script>

    <?= $this->renderSection('script') ?> 

  </body>

  </html>