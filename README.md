### Undergraduate-Thesis-Implementation-PBX

- This implementation uses Asterisk which is a free and open source framework for building communications applications and is sponsored by Sangoma.
- This implemetation represents simple simulation of IP PBX system. The three application were developed that interact with Asterisk: AGI, AMI and ARI. 


##AGI (Asterisk Gateway Interface):
    - Framework: https://phpagi.sourceforge.net/
    - AGI provides an interface between the Asterisk dialplan and an external program that wants to manipulate a channel in the dialplan. In general, the interface is synchronous.
    - Actions taken on a channel from an AGI block and do not return until the action is completed
    - About Application:
    Simple IVR, when called, will play some greeting message, ask for password if it is set, and then promt a user to enter some options.
    Options:
    1. Calling Extension
    2. Changing Password
    3. Repeat


##AMI (Asterisk Manager Interface):
    - AMI provides a mechanism to control where channels execute in the dialplan.
    - Unlike AGI, AMI is an asynchronous, event driven interface. For the most part, AMI does not provide mechanisms to control channel execution - rather, it provides
    information about the state of the channels and controls about where the channels are executing.
    - About the application:
    Simple dashboard that will track the number of users (total number of extensions), the number of active users (number of registered extensions),
    number of current calls, details about every call, recordings for every user, extension.conf file.
    - UI: https://semantic-ui.com/
    - Real-time - no database
    - Framework: https://github.com/marcelog/PAMI
    - Websocket: http://socketo.me/


##ARI (Asterisk REST Interface):
    - While AMI is good at call control and AGI is good at allowing a remote process to execute
    dialplan applications, neither of these APIs was designed to let a developer build their
    own custom communications application. ARI is an asynchronous API that allows developers
    to build communications applications by exposing the raw primitive objects in Asterisk (channels, bridges, endpoints, media, etc) - through an intuitive REST interface.
    The state of the objects being controlled by the user are conveyed via JSON events over a WebSocket.
    - Framework: https://github.com/CyCoreSystems/ari-proxy
    - About the application:
        There will be three functions: Dial, List, Join.
        1. dial will initiate a call between endpoints. There can be two or more endpoints.
        2. list will print all ongoing calls
        3. Join allows joining an ongoing call
        4. If there are two participants in bridge, than it is call and if one left, the whole bridge in destroyed.
        5. If there are three participants in bridge, than it is conference and bridge will be destroyed only if all participants left conference.
