import graphql from "babel-plugin-relay/macro";
import React from "react";
import { createFragmentContainer } from "react-relay";
import User from "./User";

const UserList = props => {
  const {
    userData: { users }
  } = props;

  return (
    <section>
      <ul>
        {users.edges.map(edge => (
          <User key={edge.node.__id} user={edge.node} />
        ))}
      </ul>
    </section>
  );
};

export default createFragmentContainer(
  UserList,
  // Each key specified in this object will correspond to a prop available to the component
  {
    userData: graphql`
      # As a convention, we name the fragment as '<ComponentFileName>_<PropName>'
      fragment UserList_userData on Query {
        users {
          edges {
            node {
              ...User_user
            }
          }
        }
      }
    `
  }
);
