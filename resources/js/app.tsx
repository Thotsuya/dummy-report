import './bootstrap';

import React from "react"
import ReactDOM from 'react-dom/client';

import Home from "@/Pages/Home";

console.log('Hi from app.tsx');

ReactDOM
    .createRoot(document.getElementById('app')).render(
    <Home />
);
