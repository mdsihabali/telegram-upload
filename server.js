const express = require('express');
const multer = require('multer');
const axios = require('axios');
const FormData = require('form-data');
require('dotenv').config();
const fs = require('fs');

const app = express();
const upload = multer({ dest: 'uploads/' });

app.use(express.static(__dirname));

app.post('/upload', upload.single('document'), async (req,res)=>{
  try{
    const file = req.file;
    const form = new FormData();
    form.append('chat_id', process.env.TELEGRAM_CHAT_ID);
    form.append('document', fs.createReadStream(file.path));

    const response = await axios.post(
      `https://api.telegram.org/bot${process.env.TELEGRAM_BOT_TOKEN}/sendDocument`,
      form,
      { headers: form.getHeaders() }
    );
    res.send('File sent! ✅');
  }catch(err){
    res.send('Error: '+err.message);
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, ()=>console.log(`Server running on port ${PORT}`));
