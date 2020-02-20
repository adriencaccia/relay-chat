import graphql from "babel-plugin-relay/macro";
import React from "react";
import { QueryRenderer } from "react-relay";
import SimpleUser from "./components/SimpleUser";
import relayEnvironment from "./relayEnvironment";

const appQuery = graphql`
  query AppQuery {
    node(id: "5e31876721e45") {
      ...SimpleUser_user
    }
  }
`;

export default class App extends React.Component {
  render() {
    return (
      <QueryRenderer
        environment={relayEnvironment}
        query={appQuery}
        variables={{}}
        render={({ error, props }) => {
          if (error) {
            return <div>Error!</div>;
          }
          if (!props) {
            return <div>Loading...</div>;
          }
          return <SimpleUser user={props.node} />;
        }}
      />
    );
  }
}
