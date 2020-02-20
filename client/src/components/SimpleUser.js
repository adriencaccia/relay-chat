import graphql from "babel-plugin-relay/macro";
import React from "react";
import { createFragmentContainer } from "react-relay";

class SimpleUser extends React.Component {
  render() {
    const { name, id } = this.props.user;
    return (
      <div>
        {id}: {name}
      </div>
    );
  }
}

export default createFragmentContainer(
  SimpleUser,
  // Each key specified in this object will correspond to a prop available to the component
  {
    user: graphql`
      # As a convention, we name the fragment as '<ComponentFileName>_<propName>'
      fragment SimpleUser_user on User {
        id
        name
      }
    `
  }
);
