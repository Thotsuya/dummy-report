import './bootstrap';

import React from "react"
import ReactDOM from 'react-dom/client';
import {ApolloProvider} from "@apollo/client";

import Home from "@/Pages/Home";
import client from "@/Config/graphql";

ReactDOM
    .createRoot(document.getElementById('app')).render(
    <ApolloProvider client={client}>
        <Home />
    </ApolloProvider>
);
