import graphql from "babel-plugin-relay/macro";
import React from "react";
import { QueryRenderer } from "react-relay";
import UserList from "./components/UserList";
import relayEnvironment from "./relayEnvironment";

export default class App extends React.Component {
  render() {
    return (
      <QueryRenderer
        environment={relayEnvironment}
        query={graphql`
          query AppQuery {
            ...UserList_userData
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
              <UserList userData={props} />
            </div>
          );
        }}
      />
    );
  }
}
