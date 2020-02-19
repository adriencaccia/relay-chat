import graphql from "babel-plugin-relay/macro";
import React from "react";
import { createPaginationContainer } from "react-relay";
import PostMessageMutation from "../mutations/PostMessageMutation";

class User extends React.Component {
  constructor(props) {
    super(props);
    this.state = { value: "" };
    this._handleInputChange = this._handleInputChange.bind(this);
    this._handleSubmit = this._handleSubmit.bind(this);
  }

  postMessage = text => {
    PostMessageMutation.commit(
      this.props.relay.environment,
      text,
      this.props.user
    );
    this.setState({ value: "" });
  };

  _handleInputChange(event) {
    this.setState({
      value: event.target.value
    });
  }

  _handleSubmit(event) {
    this.postMessage(this.state.value);
    event.preventDefault();
  }

  render() {
    const { name, messages, id } = this.props.user;
    const { value } = this.state;
    return (
      <li>
        <form onSubmit={this._handleSubmit}>
          <p>
            id {id}, name: {name}
          </p>
          <input type="text" value={value} onChange={this._handleInputChange} />
          <input type="submit" value="Send ✉" />
          <ul>
            {messages.edges.map(({ node }) => (
              <li key={node.id}>
                {node.createdAt}: {node.text}
              </li>
            ))}
          </ul>
        </form>
      </li>
    );
  }
}

export default createPaginationContainer(
  User,
  // Each key specified in this object will correspond to a prop available to the component
  {
    user: graphql`
      # As a convention, we name the fragment as '<ComponentFileName>_<propName>'
      fragment User_user on User {
        id
        name
        messages(last: 5) @connection(key: "User_messages") {
          edges {
            node {
              id
              createdAt
              text
            }
          }
        }
      }
    `
  },
  {
    getConnectionFromProps(props) {
      return props?.user?.messages;
    },
    // This is also the default implementation of `getFragmentVariables` if it isn't provided.
    getFragmentVariables(prevVars, totalCount) {
      return {
        ...prevVars,
        count: totalCount
      };
    }
  }
);
