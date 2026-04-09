import psycopg2
import pandas as pd
from twilio.rest import Client

# Conectar a la base de datos
conn = psycopg2.connect(
    host="127.0.0.1",
    database="tu_base",
    user="postgres",
    password="tu_password",
    port="5432"
)

df = pd.read_sql("SELECT nombre, telefono FROM clientes", conn)

# Configurar Twilio
account_sid = 'TU_ACCOUNT_SID'
auth_token = 'TU_AUTH_TOKEN'
client = Client(account_sid, auth_token)
twilio_number = 'whatsapp:+14155238886'

# Enviar mensajes
for _, row in df.iterrows():
    to = f"whatsapp:{row['telefono']}"
    mensaje = f"Hola {row['nombre']}, tenemos una promoción especial para ti."
    client.messages.create(from_=twilio_number, body=mensaje, to=to)
    print(f"Mensaje enviado a {row['nombre']}")