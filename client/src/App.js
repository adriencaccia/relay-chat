import graphql from "babel-plugin-relay/macro";
import React from "react";
import { QueryRenderer } from "react-relay";
import SimpleUser from "./components/SimpleUser";
import SlowComponent from "./components/SlowComponent";
import relayEnvironment from "./relayEnvironment";

export default class App extends React.Component {
  render() {
    return (
      <QueryRenderer
        environment={relayEnvironment}
        query={graphql`
          query AppQuery {
            node(id: "5e31876721e45") {
              ...SimpleUser_user
            }
            ...SlowComponent_slowResult @arguments(timeout: 1500)
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
              <SimpleUser user={props.node} />
              <SlowComponent slowResult={props} />
            </div>
          );
        }}
      />
    );
  }
}
