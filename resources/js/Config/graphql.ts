import { ApolloClient, InMemoryCache, ApolloProvider, gql } from '@apollo/client';

const client = new ApolloClient({
    uri: import.meta.env.VITE_GRAPHQL_ENDPOINT,
    cache: new InMemoryCache()
});

export default client;
