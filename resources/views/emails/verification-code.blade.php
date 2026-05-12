<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
        }
        .content {
            margin: 30px 0;
            line-height: 1.6;
            color: #333;
        }
        .code-box {
            background-color: #f9f9f9;
            border: 2px solid #000;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .code {
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #000;
            font-family: 'Courier New', monospace;
        }
        .expiration {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #856404;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #999;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🚕 MoveShift Taxi</div>
            <div class="subtitle">Servicio de taxi estudiantil</div>
        </div>

        <div class="content">
            <p>¡Hola!</p>
            
            <p>Hemos recibido una solicitud para registrar la cuenta con tu correo:</p>
            
            <p><strong>{{ $email }}</strong></p>
            
            <p>Para completar tu registro, ingresa el siguiente código de verificación:</p>
            
            <div class="code-box">
                <div class="code">{{ $code }}</div>
            </div>
            
            <div class="expiration">
                <strong>⏱️ Importante:</strong> Este código expira en 5 minutos. Si no solicitaste este registro, ignora este email.
            </div>
            
            <p>Una vez verifiques tu email, podrás iniciar sesión en MoveShift Taxi.</p>
            
            <p>¿Preguntas? Contáctanos en support@moveshift.com</p>
        </div>

        <div class="footer">
            <p>&copy; 2026 MoveShift Taxi. Todos los derechos reservados.</p>
            <p>Este es un email automático, por favor no responder a este mensaje.</p>
        </div>
    </div>
</body>
</html>