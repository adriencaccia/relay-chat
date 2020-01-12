import graphql from "babel-plugin-relay/macro";
import React from "react";
import { QueryRenderer } from "react-relay";
import relayEnvironment from "./relayEnvironment";

export default class App extends React.Component {
  render() {
    return (
      <QueryRenderer
        environment={relayEnvironment}
        query={graphql`
          query AppQuery {
            users {
              edges {
                node {
                  name
                }
                cursor
              }
            }
          }
        `}
        variables={{}}
        render={({ error, props }) => {
          if (error) {
            return <div>Error!</div>;
          }
          if (!props) {
            return <div>Loading...</div>;
          }
          return (
            <div>
              <div>Number of users: {props.users.edges.length}</div>
              <ul>
                {props.users.edges.map(edge => (
                  <li key={edge.cursor}>{edge.node.name}</li>
                ))}
              </ul>
            </div>
          );
        }}
      />
    );
  }
}
