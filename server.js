// server.js
import express from "express";
import cors from "cors";
import dotenv from "dotenv";
import { Configuration, OpenAIApi } from "openai";

dotenv.config();

const app = express();
const port = 3000;

app.use(cors());
app.use(express.json());

const configuration = new Configuration({
  apiKey: process.env.OPENAI_API_KEY,
});

const openai = new OpenAIApi(configuration);

app.post("/generate-quiz", async (req, res) => {
  try {
    const { topic, numQuestions, questionType } = req.body;

    const prompt = `
Generate ${numQuestions} ${questionType} questions about the topic: "${topic}".
Format the output as a JSON array of objects with fields: question, options (if applicable), and answer.
Example:
[
  {
    "question": "What is ...?",
    "options": ["a", "b", "c", "d"],
    "answer": "a"
  },
  ...
]
`;

    const response = await openai.createCompletion({
      model: "text-davinci-003",
      prompt: prompt,
      max_tokens: 800,
      temperature: 0.7,
    });

    const text = response.data.choices[0].text.trim();

    // Try parsing JSON
    let quizQuestions;
    try {
      quizQuestions = JSON.parse(text);
    } catch (error) {
      // If failed, send raw text for debugging
      return res.json({ success: false, message: "Failed to parse JSON", raw: text });
    }

    res.json({ success: true, questions: quizQuestions });
  } catch (error) {
    console.error(error);
    res.status(500).json({ success: false, message: "Server error" });
  }
});

app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});
