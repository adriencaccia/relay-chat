import graphql from "babel-plugin-relay/macro";
import { commitMutation } from "react-relay";
import { ConnectionHandler } from "relay-runtime";

// We start by defining our mutation from above using `graphql`
const mutation = graphql`
  mutation PostMessageMutation($input: PostMessageInput!) {
    PostMessage(input: $input) {
      message {
        id
        text
        createdAt
      }
    }
  }
`;

const sharedUpdater = (store, user, newEdge) => {
  // Get the current user record from the store
  const userProxy = store.get(user.id);

  // Get the user's Todo List using ConnectionHandler helper
  const conn = ConnectionHandler.getConnection(
    userProxy,
    "User_messages" // This is the connection identifier, defined here
  );

  // Insert the new message into the message List connection
  ConnectionHandler.insertEdgeAfter(conn, newEdge);
};

let tempID = 0;

function commit(environment, text, user) {
  // Now we just call commitMutation with the appropriate parameters
  return commitMutation(environment, {
    mutation,
    variables: {
      input: { text, userId: user.id }
    },
    updater: store => {
      // Get the payload returned from the server
      const payload = store.getRootField("PostMessage");

      // Get the node of the newly created Message record
      const node = payload.getLinkedRecord("message");

      // Create the edge of the newly created Message record
      const newEdge = store.create(
        "client:newEdge:" + node.getDataID(),
        "edge"
      );
      newEdge.setLinkedRecord(node, "node");

      // Add it to the user's message list
      sharedUpdater(store, user, newEdge);
    },
    optimisticUpdater: store => {
      // Create a Message record in our store with a temporary ID
      const id = "client:newMessage:" + tempID++;
      const node = store.create(id, "Message");
      // We specify the wrong date format in order to show how the updater works
      const createdAt = new Date().toLocaleString();
      node.setValue(text, "text");
      node.setValue(id, "id");
      node.setValue(createdAt, "createdAt");

      // Create the edge of the newly created Message record
      const newEdge = store.create(
        "client:newEdge:" + node.getDataID(),
        "edge"
      );
      newEdge.setLinkedRecord(node, "node");

      // Add it to the user's message list
      sharedUpdater(store, user, newEdge);
    }
  });
}

export default { commit };
