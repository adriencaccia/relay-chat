import graphql from "babel-plugin-relay/macro";
import React from "react";
import { QueryRenderer } from "react-relay";
import UserList from "./components/UserList";
import relayEnvironment from "./relayEnvironment";

const appQuery = graphql`
  query AppQuery {
    ...UserList_userData
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
          return <UserList userData={props} />;
        }}
      />
    );
  }
}
