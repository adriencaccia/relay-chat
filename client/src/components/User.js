import graphql from "babel-plugin-relay/macro";
import React from "react";
import { createPaginationContainer } from "react-relay";
import PostMessageMutation from "../mutations/PostMessageMutation";

class User extends React.Component {
  constructor(props) {
    super(props);
    this.state = { value: "" };
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

  _handleInputKeyDown(event) {
    if (event.key === "Enter") this.postMessage(this.state.value);
  }

  render() {
    const { name, messages, id } = this.props.user;
    const { value } = this.state;
    return (
      <li>
        <div>
          <p>
            id {id}, name: {name}
          </p>
          <input
            type="text"
            value={value}
            onChange={(event) => this._handleInputChange(event)}
            onKeyDown={(event) => this._handleInputKeyDown(event)}
          />
          <button onClick={() => this.postMessage(value)}>Send ✉</button>
          <ul>
            {messages.edges.map(({ node }) => (
              <li key={node.id}>
                {node.createdAt}: {node.text}
              </li>
            ))}
          </ul>
        </div>
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
