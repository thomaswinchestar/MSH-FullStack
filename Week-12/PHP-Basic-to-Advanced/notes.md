## Website vs Web Application
## Server-Side Programming Language
## Facebook, Wikipedia, Vimeo, Slack

## Chrome, Firefox, Edge - Web Browser

## Website -> Information and Simple Form Data
## Web App -> Post, Message, File Upload(Video, Audio, Image)

## Software Development
## Desktop Solution Development, System Software Development, Mobile App Development, ## Web Development

## PHP - Personal Home Page
## PHP: Hypertext Preprocessor
## Recursive Acronym
## GNU, WINE Is Not an Emulator(WINE)

www - World Wide Web (Web)
## Email
## FTP(File Transfer Protocol)


Web 
- Client
- Server
- Protocol

Client
- User
- Device(Phone, Tablet, Laptop)
- User Agent(Web Browser)

Server
- Server Computer(Web Server, FTP Server, Mail Server)
- Server Software

Protocol
- HTTP(Hypertext Transfer Protocol)

- User-> UserAgent(Browser)

Request/ Response Headers

Client Request
- Header
- Body

GET - Request Method

POST, PUT, PATCH, DELETE

Request Method -> URI -> Resource address

Resource-> HTML Document, Image, Video, JS Code, PDF, etc.

HTTP Version - HTTP/1.1

HTTP

max-age = 2592000(seconds) - 30 days

Stateless Protocol - HTTP - web, mobile, desktop softwares

Pull Technology - HTTP

weakness - real-time

HTTP/2 & HTTP/3

HTTP/1.0, HTTP/1.1, HTTP/2, HTTP/3

HTTP/1.1, HTTP/2, HTTP/3

TCP, UDP

HTTP/1.0 - 1 resource to 1 resource, 10 resource to 10 network connection

HTTP/1.1 - Keep Alive, only one connnection can take resource sequencly, open network connection 4 0r 5 to get resource share

HTTP/2 - Multiplex, TCP 

HTTP/3 - UDP, QUIC
---

# HTTP (HyperText Transfer Protocol) - Comprehensive Lecture Notes

> References:
> - https://http.dev/
> - https://developer.mozilla.org/en-US/docs/Web/HTTP

---

## 1. What is HTTP?

- HTTP stands for **HyperText Transfer Protocol**
- It is an **application-layer** protocol for transmitting hypermedia documents (HTML, images, text, media files)
- It is the **foundation of the World Wide Web (WWW)**
- Follows a **client-server model**: client opens a connection, makes a request, then waits for a response from the server
- HTTP is a **stateless protocol** - the server does not keep any session data between two requests
- However, **cookies** (via `Set-Cookie` / `Cookie` headers) add state to some client-server interactions
- HTTP is a **pull technology** - the client initiates requests (weakness: not ideal for real-time)
- HTTP sessions are established using **TCP** or **UDP** connections

### Key Characteristics of HTTP:
1. **Stateless** - each request is independent, no memory of previous requests
2. **Extensible** - new headers and methods can be added
3. **Text-based** (HTTP/1.x) / **Binary** (HTTP/2+)
4. **Request-Response model** - always initiated by the client

---

## 2. Evolution of HTTP - Version History

### HTTP/0.9 (1991) - "The One-Line Protocol"
- Released in **1991** by Tim Berners-Lee
- Originally had **no version number** - later called 0.9 to distinguish from future versions
- **Only supported GET method** - only one request method
- Requests consisted of **only a path** to the resource (no headers, no version info)
- Responses were **always HTML files** - no other content types
- **No HTTP headers** in request or response
- **No HTTP status codes** - if error occurred, server sent an HTML file describing the issue
- Connection was closed after every single response

**Example Request/Response:**
```
GET /index.html

<html>
Welcome to the example.re homepage!
</html>
```

### HTTP/1.0 (1996) - "The Foundation"
- Published as **RFC 1945** in 1996
- Added **protocol version specification** on the request line (e.g., `GET /index.html HTTP/1.0`)
- Introduced **HTTP request methods**: GET, HEAD, POST
  - **POST** was critical - enabled sending data from client to server (bidirectional communication)
- Introduced **HTTP Status Codes** (200, 404, 500, etc.) to indicate success/failure
- Introduced **HTTP Headers** for metadata (both request and response)
  - Enabled **caching**, **authorization**, and **content type detection**
- Introduced `Content-Type` header - allowing **non-HTML files** to be transmitted (images, text, etc.)
- **Each resource required a new TCP connection** - 10 resources = 10 connections (very inefficient!)

**Example Request/Response:**
```
GET /index.html HTTP/1.0
User-Agent: NCSA_Mosaic/2.0 (Windows 3.1)

200 OK
Date: Sun, 01 Jan 1995 12:01:00 GMT
Server: CERN/3.0 libwww/2.17
Content-Type: text/html

<html>Welcome to the example.re homepage!</html>
```

### HTTP/1.1 (1997) - "The Workhorse" (Still Widely Used)
- Published in **1997** (RFC 2068), revised in **1999** (RFC 2616), then replaced by RFCs 7230-7235 in 2014
- Released only months after HTTP/1.0 because many apps claimed "HTTP/1.0" but weren't fully compliant

**Key improvements over HTTP/1.0:**
1. **Persistent connections (Keep-Alive)** - reuse the same TCP connection for multiple requests
   - No need to open a new connection for every resource (saves overhead)
2. **Pipelining** - send a second request before the first response is complete (reduces latency)
3. **Host header** - supports virtual hosting (multiple domains on same IP address)
4. **Additional cache controls** - better caching mechanisms
5. **Content Negotiation** - client and server negotiate language, encoding, content types
6. **New HTTP methods** added (PUT, PATCH, DELETE, OPTIONS, TRACE, CONNECT)
7. **Chunked transfer encoding** - send data in chunks without knowing total size upfront

**Limitation:** Still suffers from **head-of-line blocking** - resources are fetched sequentially per connection. Browsers typically open 4-6 parallel connections as a workaround.

**Example Request/Response:**
```
GET /index.html HTTP/1.1
Host: www.example.re
User-Agent: Mozilla/5.0
Accept: text/html
Accept-Language: en-US, en; q=0.5
Accept-Encoding: gzip, deflate

200 OK
Server: Apache
Connection: Keep-Alive
Keep-Alive: timeout=5, max=500
Content-Encoding: gzip
Content-Type: text/html; charset=UTF-8
Transfer-Encoding: chunked
```

### HTTP/2 (2015) - "The Performance Leap"
- Published in **2015** as RFC 7540
- Developed by the HTTP Working Group of the **IETF** (Internet Engineering Task Force)
- Evolved from Google's **SPDY** ("speedy") protocol
- **Backward compatible** with HTTP/1.1 - same methods, status codes, and headers
- Still uses **TCP** as the transport layer

**Key features of HTTP/2:**
1. **Binary protocol** - cannot be read/written manually (unlike text-based HTTP/1.x)
2. **Multiplexing** - multiple requests/responses simultaneously over a **single** TCP connection
   - Partially solves head-of-line blocking
3. **Header compression (HPACK)** - compresses HTTP headers to reduce bandwidth
4. **Server Push** - server can proactively send resources to client before they're requested
5. **Stream prioritization** - clients can indicate priority of resources
6. **Flow control** - prevents sender from overwhelming receiver with data
7. **Message reset** - client can stop mid-action and start anew without dropping the connection
8. **Alt-Svc header** - server can suggest alternative services/routes

**Why it was quickly adopted:**
- No changes required to server-side applications or websites
- No information loss when translating between HTTP/1.1 and HTTP/2
- Bandwidth conservation = cost savings (especially for high-traffic sites)

**Development concern:** Being binary and compressed, it's harder to debug without proper tools.

### HTTP/3 (Draft → Widely Adopted) - "The Future"
- Changes the **underlying transport** from **TCP** to **QUIC** (Quick UDP Internet Connections)
- QUIC is built on top of **UDP** (originally developed by Google)
- The name evolved: "HTTP/2 Semantics Using QUIC" → "HTTP over QUIC" → **HTTP/3**
- Already supported by **majority of web browsers** and running on **millions of websites**

**Why move away from TCP?**
1. TCP requires a **full network round trip** just to establish a connection (handshake)
2. TCP suffers from **head-of-line blocking** - all streams share one byte stream, so one lost packet delays everything
3. TCP is deeply embedded in OS kernels and middleboxes - **very hard to update**

**Key features of HTTP/3 (via QUIC):**
1. **Built on UDP** - avoids TCP's inherent limitations
2. **Multiplexing without head-of-line blocking** - streams are truly independent
3. **Faster connection setup** - TLS handshake is integrated (fewer round trips)
4. **0-RTT (Zero Round Trip Time) Resumption** - reconnect to known servers instantly
5. **Connection migration** - maintains connection when switching networks (e.g., Wi-Fi to cellular)
   - Uses a **Connection ID (CID)** instead of the TCP 4-tuple (IP+port pairs)
6. **Header compression (QPACK)** - designed to work with streams (vs HPACK in HTTP/2)
7. **Built-in TLS 1.3 encryption** - security is not a separate layer anymore
8. **Anti-amplification limit** - protection against DDoS attacks (3x data limit before verification)

**Tradeoffs:**
- QUIC uses more **CPU time** than TCP (UDP not as optimized yet)
- Packet inspection by firewalls is harder due to encryption
- Only works with **official TLS certificates** (no self-signed certs)

---

## 3. HTTP Version Comparison Table

| Feature | HTTP/0.9 | HTTP/1.0 | HTTP/1.1 | HTTP/2 | HTTP/3 |
|---|---|---|---|---|---|
| Year | 1991 | 1996 | 1997 | 2015 | Draft/Adopted |
| Transport | TCP | TCP | TCP | TCP | **UDP (QUIC)** |
| Methods | GET only | GET, HEAD, POST | All 9 methods | Same as 1.1 | Same as 1.1 |
| Headers | None | Yes | Yes (expanded) | Yes (compressed) | Yes (QPACK) |
| Status Codes | None | Yes | Yes (expanded) | Same as 1.1 | Same as 1.1 |
| Connection | 1 request per connection | 1 request per connection | **Keep-Alive** (persistent) | **Multiplexed** | **Multiplexed** |
| Format | Text | Text | Text | **Binary** | **Binary** |
| Encryption | None | None | Optional | Optional (usually TLS) | **Built-in TLS 1.3** |
| Head-of-line blocking | N/A | Yes | Partially (pipelining) | Partially solved | **Fully solved** |

---

## 4. HTTP Request Methods (HTTP Verbs)

### The 9 Standard Methods:

| Method | Safe | Idempotent | Cacheable | Description |
|---|---|---|---|---|
| **GET** | Yes | Yes | Yes | Retrieve a resource - the primary operation for most servers |
| **HEAD** | Yes | Yes | Yes | Same as GET but returns **only headers** (no body) |
| **OPTIONS** | Yes | Yes | No | Ask what operations are available for a resource |
| **TRACE** | Yes | Yes | No | Perform a loopback test on the path to the resource |
| **DELETE** | No | Yes | No (invalidates cache) | Remove the specified resource from the server |
| **PUT** | No | Yes | No (invalidates cache) | Replace the entire resource (or create it) |
| **POST** | No | No | Sometimes | Submit data to interact with a resource (create, process) |
| **PATCH** | No | No | Sometimes | Partially modify a resource (unlike PUT which replaces entirely) |
| **CONNECT** | No | No | No | Establish a tunnel to the origin server (used for HTTPS proxying) |

### Method Properties Explained:

**Safe methods** = Read-only; won't change server state (GET, HEAD, OPTIONS, TRACE)
- Even if side effects occur (e.g., visitor counter), the client didn't request the change

**Idempotent methods** = Same result if called multiple times (GET, HEAD, OPTIONS, TRACE, DELETE, PUT)
- All safe methods are idempotent, but DELETE and PUT are idempotent AND unsafe
- Example: DELETE /file → 200 OK first time, 404 Not Found second time → same end state

**Cacheable methods** = Response can be stored and reused (GET, HEAD; POST/PATCH sometimes)

### CRUD Mapping (REST API Pattern):
| CRUD Operation | HTTP Method |
|---|---|
| **C**reate | POST (or PUT) |
| **R**ead | GET |
| **U**pdate (full) | PUT |
| **U**pdate (partial) | PATCH |
| **D**elete | DELETE |

**Important:** General-purpose servers are **required** to support GET and HEAD. All other methods are optional.

---

## 5. HTTP Status Codes

HTTP response codes indicate the result of a request. The **first digit** identifies the category:

### 1xx - Informational (Request continuing)
- `100 Continue` - Server received request headers, client should proceed with body
- `101 Switching Protocols` - Server is switching protocols (e.g., to WebSocket)
- `103 Early Hints` - Allows preloading resources while server prepares response

### 2xx - Success (Request received, understood, accepted)
- `200 OK` - Standard success response
- `201 Created` - New resource was successfully created (common after POST)
- `204 No Content` - Success but no response body (common after DELETE)
- `206 Partial Content` - Server is delivering part of the resource (range requests)

### 3xx - Redirection (Further action needed)
- `301 Moved Permanently` - Resource has permanently moved to new URL (SEO: transfers link juice)
- `302 Found` - Temporary redirect
- `304 Not Modified` - Cached version is still valid (saves bandwidth!)
- `307 Temporary Redirect` - Like 302 but **preserves the HTTP method**
- `308 Permanent Redirect` - Like 301 but **preserves the HTTP method**

### 4xx - Client Error (Problem with the request)
- `400 Bad Request` - Malformed request syntax
- `401 Unauthorized` - Authentication required (misleading name - means "unauthenticated")
- `403 Forbidden` - Server understood request but refuses to authorize it
- `404 Not Found` - Resource does not exist (most well-known error!)
- `405 Method Not Allowed` - HTTP method not supported for this resource
- `408 Request Timeout` - Server timed out waiting for the request
- `409 Conflict` - Request conflicts with current state of resource
- `418 I'm a Teapot` - April Fools' joke from RFC 2324 (still in some servers!)
- `429 Too Many Requests` - Rate limiting (client sent too many requests)
- `451 Unavailable For Legal Reasons` - Censorship/legal block (named after Fahrenheit 451)

### 5xx - Server Error (Server failed to fulfill valid request)
- `500 Internal Server Error` - Generic server error (catch-all)
- `502 Bad Gateway` - Server acting as gateway received invalid response
- `503 Service Unavailable` - Server temporarily overloaded or under maintenance
- `504 Gateway Timeout` - Gateway/proxy did not get a response in time

---

## 6. HTTP Headers - Detailed Categories

HTTP headers are **key-value pairs** that exchange metadata between client, server, and intermediaries.

### Header Types by Scope:
- **End-to-end headers** - Must be transmitted to the final recipient (not modified by intermediaries)
- **Hop-by-hop headers** - Only relevant between two nodes (not forwarded or cached)

### Header Categories:

#### Authentication Headers
- `Authorization` - Client sends credentials to the server
- `WWW-Authenticate` - Server tells client what auth scheme to use
- `Proxy-Authenticate` / `Proxy-Authorization` - Same for proxy authentication

#### Caching Headers
- `Cache-Control` - Directives for caching (e.g., `max-age=2592000` = 30 days)
- `Expires` - Date/time after which response is considered stale
- `ETag` - Unique identifier for a specific version of a resource
- `Last-Modified` - When the resource was last changed
- `Age` - Time in seconds the resource has been in cache

#### Content Headers (Message Body Information)
- `Content-Type` - Media type of the body (e.g., `text/html`, `application/json`, `image/png`)
- `Content-Length` - Size of the body in bytes
- `Content-Encoding` - Compression algorithm used (e.g., `gzip`, `deflate`, `br`)
- `Content-Language` - Language(s) intended for the audience
- `Content-Disposition` - How to display the content (e.g., `attachment; filename="file.pdf"`)

#### Connection Management
- `Connection` - Control options (e.g., `Keep-Alive`, `close`)
- `Keep-Alive` - Parameters: `timeout=5, max=500`

#### Content Negotiation
- `Accept` - Media types the client accepts (e.g., `text/html, application/json`)
- `Accept-Encoding` - Compression algorithms the client supports
- `Accept-Language` - Languages the client prefers (e.g., `en-US, en; q=0.5`)

#### Cookies
- `Set-Cookie` - Server tells client to store a cookie
- `Cookie` - Client sends stored cookies back to server

#### CORS (Cross-Origin Resource Sharing)
- `Access-Control-Allow-Origin` - Which origins can access the resource
- `Access-Control-Allow-Methods` - Allowed HTTP methods for CORS
- `Access-Control-Allow-Headers` - Allowed headers for CORS
- `Access-Control-Max-Age` - How long preflight results can be cached

#### Request Context
- `Host` - Domain name of the server (required in HTTP/1.1+)
- `User-Agent` - Identifies the client software (browser, bot, etc.)
- `Referer` - URL of the page that linked to the current request (note: intentional misspelling!)
- `Origin` - Where the request originated from

#### Security Headers
- `Strict-Transport-Security` (HSTS) - Force HTTPS connections
- `Content-Security-Policy` (CSP) - Control which resources the client can load
- `X-Frame-Options` - Prevent clickjacking (`DENY`, `SAMEORIGIN`)
- `X-Content-Type-Options` - Prevent MIME type sniffing (`nosniff`)
- `X-XSS-Protection` - XSS filter control
- `Cross-Origin-Resource-Policy` (CORP) - Protect against speculative side-channel attacks

#### Transfer/Encoding
- `Transfer-Encoding` - How the message body is encoded for transfer (e.g., `chunked`)

---

## 7. HTTP Request/Response Structure

### HTTP Request Structure:
```
[Method] [URI] [HTTP Version]     ← Request Line
[Header-Name]: [Header-Value]     ← Headers (multiple)
[Header-Name]: [Header-Value]
                                   ← Empty line (CRLF)
[Request Body]                     ← Optional (for POST, PUT, PATCH)
```

**Example:**
```
POST /api/users HTTP/1.1
Host: www.example.com
Content-Type: application/json
Content-Length: 42
Authorization: Bearer eyJhbGciOi...

{"name": "John", "email": "john@test.com"}
```

### HTTP Response Structure:
```
[HTTP Version] [Status Code] [Reason Phrase]  ← Status Line
[Header-Name]: [Header-Value]                  ← Headers (multiple)
[Header-Name]: [Header-Value]
                                                ← Empty line (CRLF)
[Response Body]                                 ← The actual content
```

**Example:**
```
HTTP/1.1 200 OK
Server: Apache
Content-Type: application/json
Content-Length: 58
Cache-Control: max-age=3600

{"id": 1, "name": "John", "email": "john@test.com"}
```

---

## 8. HTTPS - HTTP Secure

- **HTTPS** = HTTP + **TLS** (Transport Layer Security) encryption
- Uses port **443** (HTTP uses port **80**)
- All data between client and server is **encrypted**
- Provides: **Confidentiality**, **Integrity**, and **Authentication**
- Identified by the **lock icon** in the browser address bar
- Required for sensitive data (passwords, credit cards, personal info)
- Google uses HTTPS as a **ranking signal** for SEO

---

## 9. Important HTTP Concepts

### Caching
- Mechanism for **storing and reusing responses** to reduce latency and bandwidth
- Controlled by `Cache-Control`, `Expires`, `ETag`, `Last-Modified` headers
- `max-age=2592000` = cache for 30 days (2,592,000 seconds)
- `no-cache` = must revalidate before using cached version
- `no-store` = never cache this response

### Cookies & State Management
- HTTP is **stateless** by design
- **Cookies** add state management via `Set-Cookie` (response) and `Cookie` (request) headers
- Used for: session management, user preferences, tracking
- Attributes: `Expires`, `Max-Age`, `Domain`, `Path`, `Secure`, `HttpOnly`, `SameSite`

### CORS (Cross-Origin Resource Sharing)
- Security mechanism controlling how resources on one origin can be requested from another
- **Preflight request** (OPTIONS) is sent for certain "non-simple" requests
- Key headers: `Access-Control-Allow-Origin`, `Access-Control-Allow-Methods`

### Content Negotiation
- Client and server agree on the best representation of a resource
- Client sends `Accept`, `Accept-Language`, `Accept-Encoding` headers
- Server responds with the most suitable version

### URI, URL, URN
- **URI** (Uniform Resource Identifier) - generic identifier for a resource
- **URL** (Uniform Resource Locator) - identifies resource by **location** (e.g., `https://example.com/page`)
- **URN** (Uniform Resource Name) - identifies resource by **name** (e.g., `urn:isbn:0451450523`)
- Every URL is a URI, but not every URI is a URL

---

## 10. HTTP Testing Tools

| Tool | Purpose | URL |
|---|---|---|
| HTTP Status Tester | Test HTTP responses and redirect chains | https://http.app/ |
| HTTP Compression Check | Check if HTTP compression is enabled | https://httpcompression.com/ |
| HTTP/2 Check | Check if HTTP/2 is enabled | https://http2.org/ |
| HTTP/3 Check | Check if HTTP/3 is enabled | https://http3.net/ |
| HTTP Response API | Test code reactions to varying HTTP responses | https://http.codes/ |
| URL Parse API | Parse any URL into its components | https://urlparse.com/ |
| Content-Type Check | Validate Content-Type headers and MIME types | https://contenttype.net/ |
| **curl** | Command-line HTTP client | `curl -v https://example.com` |
| **Postman** | GUI-based API testing tool | https://postman.com/ |
| **Browser DevTools** | Network tab for inspecting HTTP traffic | F12 → Network tab |

---

## 11. Key Facts & Quick Review

1. HTTP was created by **Tim Berners-Lee** at CERN in **1991**
2. HTTP is **stateless** - each request is independent
3. HTTP is a **pull technology** - client always initiates
4. **HTTP/1.0** introduced headers, status codes, and POST method
5. **HTTP/1.1** introduced Keep-Alive, pipelining, Host header, and chunked encoding
6. **HTTP/2** introduced binary framing, multiplexing, header compression (HPACK), and server push
7. **HTTP/3** switched from TCP to **QUIC (UDP)**, solving head-of-line blocking completely
8. **GET** is safe, idempotent, and cacheable; **POST** is none of these by default
9. **PUT** replaces the entire resource; **PATCH** modifies part of it
10. Status codes: **2xx** = success, **3xx** = redirect, **4xx** = client error, **5xx** = server error
11. The most common status codes: **200** (OK), **301** (Moved), **404** (Not Found), **500** (Server Error)
12. `Cache-Control: max-age=2592000` = 30 days of caching
13. HTTPS uses **TLS encryption** on port **443**
14. HTTP/2 uses **HPACK** for header compression; HTTP/3 uses **QPACK**
15. HTTP/3's QUIC supports **0-RTT resumption** and **connection migration** between networks

---

## 12. TCP vs UDP in HTTP Context

| Feature | TCP | UDP |
|---|---|---|
| Connection | Connection-oriented (handshake) | Connectionless |
| Reliability | Guaranteed delivery, ordered | No guarantee |
| Speed | Slower (overhead) | Faster (less overhead) |
| Used by | HTTP/1.0, HTTP/1.1, HTTP/2 | HTTP/3 (via QUIC) |
| Head-of-line blocking | Yes | No |

**QUIC** builds reliability ON TOP of UDP, giving the best of both worlds:
- Fast like UDP + Reliable like TCP + Encrypted like TLS

---