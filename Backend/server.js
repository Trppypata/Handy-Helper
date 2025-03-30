require('dotenv').config();
const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const { User } = require('./models');

const app = express();
app.use(cors());
app.use(bodyParser.json());

app.get('/', (req, res) => res.send('API is running...'));


app.get('/users', async (req, res) => {
  const users = await User.findAll();
  res.json(users);
});


app.post('/users', async (req, res) => {
  const { name, email, password } = req.body;
  const user = await User.create({ name, email, password });
  res.json(user);
});

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
