import graphql from "babel-plugin-relay/macro";
import React, { Fragment } from "react";
import { createFragmentContainer } from "react-relay";
import UserList from "./UserList";

class SlowComponent extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      displayUsers: false
    };
  }

  componentDidMount() {
    setTimeout(() => {
      this.setState({ displayUsers: true });
    }, 1000);
  }

  render() {
    const {
      slowResult: {
        slowQuery: { result }
      }
    } = this.props;
    const { displayUsers } = this.state;

    return (
      <Fragment>
        <p>{result}</p>
        {displayUsers && <UserList userData={this.props.slowResult} />}
      </Fragment>
    );
  }
}

export default createFragmentContainer(
  SlowComponent,
  // Each key specified in this object will correspond to a prop available to the component
  {
    slowResult: graphql`
      # As a convention, we name the fragment as '<ComponentFileName>_<propName>'
      fragment SlowComponent_slowResult on Query
        @argumentDefinitions(timeout: { type: "Int" }) {
        slowQuery(timeout: $timeout) {
          result
        }
        ...UserList_userData
      }
    `
  }
);
